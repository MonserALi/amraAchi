from django.urls import re_path
from .consumers import SOSConsumer
websocket_urlpatterns = [
    re_path(r"ws/sos/(?P<sos_id>\w+)/$", SOSConsumer.as_asgi()),
]
