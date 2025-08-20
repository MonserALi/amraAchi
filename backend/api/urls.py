from django.urls import path, include
from rest_framework.routers import DefaultRouter
from .views import HospitalViewSet, ICUAdminViewSet, SOSViewSet, snap_route

router = DefaultRouter()
router.register(r"hospitals", HospitalViewSet, basename="hospitals")
router.register(r"icu-admin", ICUAdminViewSet, basename="icu-admin")
router.register(r"sos", SOSViewSet, basename="sos")

urlpatterns = [
    path("api/", include(router.urls)),
    path("api/snap-route", snap_route),
    path("api/sos/<int:sos_id>/snap-route", snap_route),
]
