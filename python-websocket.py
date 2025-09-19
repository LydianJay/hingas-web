import asyncio
from aiohttp import web
import websockets

clients = set()

# WebSocket handler
async def ws_handler(ws):  # remove 'path'
    clients.add(ws)
    try:
        async for msg in ws:
            pass  # do nothing for now
    finally:
        clients.remove(ws)

async def start_ws_server():
    async with websockets.serve(ws_handler, "0.0.0.0", 8765):
        print("WebSocket server running on ws://0.0.0.0:8765")
        await asyncio.Future()  # run forever

# HTTP server
async def rfid_post(request):
    # Try form data first
    data = await request.post()
    rfid_value = data.get("rfid")

    # If not in form, try JSON
    if not rfid_value:
        try:
            json_data = await request.json()
            rfid_value = json_data.get("rfid")
        except:
            pass

    if rfid_value:
        if clients:
            await asyncio.gather(*(ws.send(rfid_value) for ws in clients))
            print(f"Broadcasted RFID: {rfid_value}")
        return web.Response(text=f"RFID {rfid_value} forwarded to WebSocket clients")
    
    return web.Response(text="Missing 'rfid' parameter", status=400)


app = web.Application()
app.add_routes([web.post("/rfid", rfid_post)])

async def start_http_server():
    runner = web.AppRunner(app)
    await runner.setup()
    site = web.TCPSite(runner, "0.0.0.0", 8000)
    await site.start()
    print("HTTP server running on http://0.0.0.0:8000")

# Run both servers
async def main():
    await asyncio.gather(
        start_ws_server(),
        start_http_server()
    )

asyncio.run(main())
