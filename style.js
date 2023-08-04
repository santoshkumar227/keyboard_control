$(document).ready(function () {
    function pollServer() {
        $.ajax({
            url: 'server.php',
            method: 'POST',
            data: { action: 'poll' },
            success: function (response) {
                const jsonString = JSON.parse(response);
                let length = jsonString.length;
                //
                const allNumbers = Array.from({ length: 10 }, (_, index) => index + 1);
                let givenNumbers = [];
                
                let htmlString = '';
                for(i=0; i<length;i++){
                    const keyElement = $(`.key[data-key="${jsonString[i]['key_number']}"]`);
                    keyElement.css('background-color', jsonString[i]['color']);
                    givenNumbers.push(jsonString[i]['key_number']);
                }
                const remainingNumbers = allNumbers.filter(number => !givenNumbers.includes(String(number)));

                // Set background color to white for remaining numbers
                $(".key").each(function () {
                    const currentNumber = parseInt($(this).data('key'));
                    if (remainingNumbers.includes(currentNumber)) {
                        $(this).css("background-color", "white");
                    }
                });

                document.querySelector('.keyboard_users').innerHTML = htmlString;
                $(".keyboard_users").html(htmlString);

                document.addEventListener('DOMContentLoaded', function() {
                    // Loop through the JSON data
                    jsonData.forEach(function(data) {
                        const keyNumber = parseInt(data.key_number);
                        const keyElement = document.querySelector(`[data-key="${key_number}"]`);
        
                        // Add classes to the key element based on the color value
                        if (data.color === "red") {
                            keyElement.classList.add('red');
                        } else if (data.color === "yellow") {
                            keyElement.classList.add('yellow');
                        }
                    });
                });


            }
        });
    }
    setInterval(pollServer, 2000);


    const myButton = $(".key");
    myButton.dblclick(function () {
        // alert("Double-clicked!");
    });

});

function getQueryParam(name) {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(name);
}
