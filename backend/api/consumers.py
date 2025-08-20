import json
from channels.generic.websocket import AsyncWebsocketConsumer

class SOSConsumer(AsyncWebsocketConsumer):
    async def connect(self):
        self.sos_id = self.scope["url_route"]["kwargs"]["sos_id"]
        self.group = f"sos_{self.sos_id}"
        await self.channel_layer.group_add(self.group, self.channel_name)
        await self.accept()

    async def disconnect(self, close_code):
        await self.channel_layer.group_discard(self.group, self.channel_name)

    async def receive(self, text_data=None, bytes_data=None):
        data = json.loads(text_data or "{}")
        # e.g., driver sends {"type":"location","lat":..,"lng":..}
        await self.channel_layer.group_send(self.group, {"type":"broadcast", "payload": data})

    async def broadcast(self, event):
        await self.send(text_data=json.dumps(event["payload"]))
