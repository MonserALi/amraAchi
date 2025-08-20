from rest_framework import serializers
from .models import Hospital, ICUBedStatus, SOSRequest

class HospitalSerializer(serializers.ModelSerializer):
    class Meta: model = Hospital; fields = "__all__"

class ICUSerializer(serializers.ModelSerializer):
    hospital = HospitalSerializer(read_only=True)
    class Meta: model = ICUBedStatus; fields = "__all__"

class SOSSerializer(serializers.ModelSerializer):
    class Meta: model = SOSRequest; fields = "__all__"
