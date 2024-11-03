$(document).ready(function() {
    $('#reserve').click(function () {
        // passed from frontend
    });
    let reservation = {
        partySize: 2,
        reservationDate: '2024-11-05',
        reservationTime: '13:30',
        tableNumber: 1
    };
    addReservation(reservation);    
});

function addReservation(reservation) {
    $.ajax({
        url: "frontend/ajax/reservation.php",  
        data: {
            // key   : value
            'reservation' : reservation
        },
        success: function(data){   
            $('#content').html(data);
        } 
    });
}