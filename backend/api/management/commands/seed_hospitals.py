from django.core.management.base import BaseCommand
import csv
from api.models import Hospital, ICUBedStatus

class Command(BaseCommand):
    help = "Seed hospitals from CSV with columns: hospital_id,name,location,lat,lng,total,available"

    def add_arguments(self, parser):
        parser.add_argument("--file", required=True)

    def handle(self, *args, **opts):
        with open(opts["file"]) as f:
            reader = csv.DictReader(f)
            for r in reader:
                h, _ = Hospital.objects.update_or_create(
                    hospital_id=r["hospital_id"],
                    defaults={"name":r["name"],"location":r.get("location",""),
                              "lat":float(r.get("lat") or 0), "lng":float(r.get("lng") or 0)})
                ICUBedStatus.objects.update_or_create(
                    hospital=h,
                    defaults={"total":int(r.get("total") or 0),
                              "available":int(r.get("available") or 0)})
        self.stdout.write(self.style.SUCCESS("Seeded hospitals"))
