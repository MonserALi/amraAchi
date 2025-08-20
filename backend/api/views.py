from django.shortcuts import render

from rest_framework import viewsets, permissions
from rest_framework.decorators import action, api_view, permission_classes
from rest_framework.response import Response
from django.utils import timezone
from django.db import connection
from django.views.decorators.csrf import csrf_exempt
from django.http import JsonResponse
import requests, json, os

from .models import Hospital, ICUBedStatus, SOSRequest, Ambulance
from .serializers import HospitalSerializer, ICUSerializer, SOSSerializer

class HospitalViewSet(viewsets.ReadOnlyModelViewSet):
    queryset = Hospital.objects.all()
    serializer_class = HospitalSerializer
    permission_classes = [permissions.AllowAny]

    @action(detail=True, methods=["get"], permission_classes=[permissions.AllowAny])
    def icu(self, request, pk=None):
        st = ICUBedStatus.objects.get(hospital_id=pk)
        return Response(ICUSerializer(st).data)

class ICUAdminViewSet(viewsets.ModelViewSet):
    queryset = ICUBedStatus.objects.select_related("hospital")
    serializer_class = ICUSerializer
    permission_classes = [permissions.IsAuthenticated]  # role check in real code

class SOSViewSet(viewsets.ModelViewSet):
    queryset = SOSRequest.objects.all().order_by("-created_at")
    serializer_class = SOSSerializer
    permission_classes = [permissions.IsAuthenticated]

    @action(detail=True, methods=["post"])
    def cancel(self, request, pk=None):
        sos = self.get_object(); sos.status="cancelled"; sos.save()
        return Response({"ok": True})

@csrf_exempt
def snap_route(request, sos_id=None):
    body = json.loads(request.body)
    coords = body["coordinates"]  # [[lat, lng], ...]
    coord_str = ";".join([f"{lng},{lat}" for (lat,lng) in coords])

    osrm = os.getenv("OSRM_URL","http://127.0.0.1:5000")
    url = f"{osrm}/route/v1/driving/{coord_str}"
    params = {"geometries":"geojson","overview":"full","steps":"false"}

    try:
        r = requests.get(url, params=params, timeout=4)
        route = r.json()["routes"][0]
        # optionally persist to SOS
        if sos_id:
            sos = SOSRequest.objects.get(id=sos_id)
            sos.path_geojson = route["geometry"]
            sos.save()
        return JsonResponse({"geometry": route["geometry"],
                             "distance": route.get("distance"),
                             "duration": route.get("duration")})
    except Exception:
        return JsonResponse({"geometry": {"type":"LineString",
                "coordinates": [[lng,lat] for (lat,lng) in coords]}}, status=200)

