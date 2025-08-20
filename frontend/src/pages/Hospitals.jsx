import { useEffect, useState } from 'react'
export default function Hospitals(){
  const [rows, setRows] = useState([])
  useEffect(()=>{ fetch('http://localhost:8000/api/hospitals/')
    .then(r=>r.json()).then(setRows) },[])
  return (
    <div className="p-3">
      <h5>ICU Availability</h5>
      <table className="table table-sm table-striped">
        <thead><tr><th>ID</th><th>Name</th><th>Location</th></tr></thead>
        <tbody>{rows.map(r=>(
          <tr key={r.hospital_id}><td>{r.hospital_id}</td><td>{r.name}</td><td>{r.location||'-'}</td></tr>
        ))}</tbody>
      </table>
    </div>
  )
}
