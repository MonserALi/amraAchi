import { Routes, Route, Link } from 'react-router-dom'
import SOSMap from './pages/SOSMap.jsx'
import Hospitals from './pages/Hospitals.jsx'

export default function App(){
  return (
    <div className="container-fluid p-0">
      <nav className="navbar navbar-light bg-light px-3">
        <span className="navbar-brand">AmraAchi</span>
        <div>
          <Link to="/" className="btn btn-sm btn-outline-primary me-2">SOS</Link>
          <Link to="/hospitals" className="btn btn-sm btn-outline-secondary">ICU</Link>
        </div>
      </nav>
      <Routes>
        <Route path="/" element={<SOSMap/>}/>
        <Route path="/hospitals" element={<Hospitals/>}/>
      </Routes>
    </div>
  )
}
