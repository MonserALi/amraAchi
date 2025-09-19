// ====== SOS Button Functionality ======
document.addEventListener('DOMContentLoaded', function () {
  const sosBtn = document.getElementById('sos-btn');

  if (sosBtn) {
    sosBtn.addEventListener('click', function () {
      // Get user's location if possible
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
          position => {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;

            // Send SOS request with location
            sendSOSRequest(lat, lng);
          },
          error => {
            // Send SOS request without location
            sendSOSRequest(null, null);
          }
        );
      } else {
        // Send SOS request without location
        sendSOSRequest(null, null);
      }
    });
  }

  function sendSOSRequest(lat, lng) {
    // Show confirmation dialog
    const confirmSOS = confirm('Are you sure you want to send an SOS emergency request?');

    if (confirmSOS) {
      // Prepare data
      const data = {
        lat: lat,
        lng: lng,
        timestamp: new Date().toISOString()
      };

      // Send SOS request to server
      fetch('api/sendSOS.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
      })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            alert('SOS request sent successfully! Help is on the way.');

            // If phone number is available, make a call
            if (data.phone) {
              window.open(`tel:${data.phone}`, '_self');
            }
          } else {
            alert('Error sending SOS request. Please call emergency services directly.');
          }
        })
        .catch(error => {
          console.error('Error sending SOS request:', error);
          alert('Error sending SOS request. Please call emergency services directly.');
        });
    }
  }
});