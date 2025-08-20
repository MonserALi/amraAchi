import L from 'leaflet'
import 'leaflet/dist/leaflet.css'
import 'leaflet-draw/dist/leaflet.draw.css'
import 'leaflet-draw'

import { useEffect, useRef } from 'react'
/* global L */
export default function SOSMap(){
  const mapRef = useRef(null), drawnRef = useRef(null)

  useEffect(()=> {
    const map = L.map('map').setView([23.7808, 90.2792], 12)
    mapRef.current = map
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{maxZoom:19}).addTo(map)

    const drawn = new L.FeatureGroup().addTo(map); drawnRef.current = drawn
    const drawControl = new L.Control.Draw({
      draw:{ marker:false, circle:false, rectangle:false, polygon:false, circlemarker:false, polyline:true },
      edit:{ featureGroup: drawn }
    })
    map.addControl(drawControl)

    async function snapAndReplace(layer){
      const latlngs = layer.getLatLngs().map(ll => [ll.lat, ll.lng])
      const res = await fetch('http://localhost:8000/api/snap-route', {
        method:'POST', headers:{'Content-Type':'application/json'},
        body: JSON.stringify({coordinates: latlngs})
      })
      const data = await res.json()
      drawn.removeLayer(layer)
      L.geoJSON(data.geometry,{style:{weight:5}}).addTo(drawn)
    }

    map.on(L.Draw.Event.CREATED, async (e)=>{ const layer = e.layer; drawn.addLayer(layer); try{ await snapAndReplace(layer) }catch{} })
    map.on(L.Draw.Event.EDITED, async (e)=>{ e.layers.eachLayer(async (layer)=>{ try{ await snapAndReplace(layer) }catch{} }) })

    return ()=> map.remove()
  },[])

  return (
    <div className="p-2">
      <div id="map" style={{height:"70vh"}} />
      <div className="text-muted small mt-2">Tip: draw a line— we’ll snap it to roads.</div>
    </div>
  )
}
