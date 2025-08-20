
from django.db import models
from django.contrib.auth.models import User

class Profile(models.Model):
    ROLE_CHOICES = [("patient","patient"),("driver","driver"),("hospital","hospital"),("doctor","doctor"),("admin","admin")]
    user = models.OneToOneField(User, on_delete=models.CASCADE)
    role = models.CharField(max_length=16, choices=ROLE_CHOICES)
    phone = models.CharField(max_length=20, unique=True)
    lang = models.CharField(max_length=5, default="bn")  # "bn" or "en"

class DriverKYC(models.Model):
    user = models.OneToOneField(User, on_delete=models.CASCADE)
    nid_no = models.CharField(max_length=32)
    license_no = models.CharField(max_length=32)
    nid_image = models.ImageField(upload_to="kyc/")
    license_image = models.ImageField(upload_to="kyc/")
    status = models.CharField(max_length=16, default="pending")  # pending/verified/rejected
    reviewed_by = models.ForeignKey(User, null=True, blank=True, on_delete=models.SET_NULL, related_name="+")
    reviewed_at = models.DateTimeField(null=True, blank=True)

class Hospital(models.Model):
    hospital_id = models.CharField(max_length=10, unique=True)
    name = models.CharField(max_length=120)
    location = models.CharField(max_length=120, blank=True) # textual (Dhaka)
    lat = models.FloatField(null=True, blank=True)
    lng = models.FloatField(null=True, blank=True)
    contact_phone = models.CharField(max_length=20, blank=True)

class ICUBedStatus(models.Model):
    hospital = models.OneToOneField(Hospital, on_delete=models.CASCADE)
    total = models.IntegerField(default=0)
    available = models.IntegerField(default=0)
    on_hold = models.IntegerField(default=0)
    updated_at = models.DateTimeField(auto_now=True)

class Ambulance(models.Model):
    driver = models.OneToOneField(User, on_delete=models.CASCADE)
    plate_no = models.CharField(max_length=20)
    status = models.CharField(max_length=16, default="idle")
    last_lat = models.FloatField(null=True, blank=True)
    last_lng = models.FloatField(null=True, blank=True)
    last_seen = models.DateTimeField(auto_now=True)

class SOSRequest(models.Model):
    STATUS = [("open","open"),("assigned","assigned"),("arrived","arrived"),("cancelled","cancelled")]
    patient = models.ForeignKey(User, on_delete=models.CASCADE)
    lat = models.FloatField(); lng = models.FloatField()
    radius_km = models.FloatField(default=2)
    assigned_driver = models.ForeignKey(User, null=True, blank=True, on_delete=models.SET_NULL, related_name="+")
    status = models.CharField(max_length=16, choices=STATUS, default="open")
    created_at = models.DateTimeField(auto_now_add=True)
    # GeoJSON LineString for patient path (snapped)
    path_geojson = models.JSONField(null=True, blank=True)

class Doctor(models.Model):
    name = models.CharField(max_length=80)
    specialty = models.CharField(max_length=80)
    hospital = models.ForeignKey(Hospital, on_delete=models.CASCADE)

class Slot(models.Model):
    doctor = models.ForeignKey(Doctor, on_delete=models.CASCADE)
    start_at = models.DateTimeField()
    capacity = models.IntegerField(default=1)
    booked = models.IntegerField(default=0)

class Appointment(models.Model):
    patient = models.ForeignKey(User, on_delete=models.CASCADE)
    slot = models.ForeignKey(Slot, on_delete=models.CASCADE)
    status = models.CharField(max_length=16, default="booked")

class PatientRecord(models.Model):
    owner = models.ForeignKey(User, on_delete=models.CASCADE)
    file = models.FileField(upload_to="records/")
    file_type = models.CharField(max_length=32, blank=True)
    uploaded_at = models.DateTimeField(auto_now_add=True)
