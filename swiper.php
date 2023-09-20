<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Swipe Button</title>
    <style>
        /* CSS for the swiping area */
        body {
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #f0f0f0; /* Background color for the entire page */
        }

        /* CSS for the swipe button */
        .swipe-container {
            position: relative;
            width: 200px;
            height: 100px;
            background-color: #3498db; /* Background color for the swiping area */
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            overflow: hidden;
        }

        .swipe-button {
            width: 50px;
            height: 100px;
            background-color: #fff; /* Background color for the button */
            color: #3498db;
            text-align: center;
            font-weight: bold;
            cursor: pointer;
            transition: transform 0.2s, background-color 0.3s;
        }
    </style>
</head>
<body>
    <div class="swipe-container">
        <div class="swipe-button" id="swipeButton">➡️</div>
    </div>

    <script>
        const swipeButton = document.getElementById('swipeButton');
        let isSwiping = false;
        let startTransform = 0;
        let startX = 0;

        // Add touchstart event listener
        swipeButton.addEventListener('touchstart', (e) => {
            isSwiping = true;
            startTransform = 0;
            startX = e.touches[0].clientX;
        });

        // Add touchmove event listener
        swipeButton.addEventListener('touchmove', (e) => {
            if (!isSwiping) return;

            const currentX = e.touches[0].clientX;
            const diff = currentX - startX;
            const maxTransform = 150; // Adjust this value for desired movement range

            if (diff > 0 && startTransform < maxTransform) {
                startTransform += diff;
            }

            swipeButton.style.transform = `translateX(${startTransform}px)`;
        });

        // Add touchend event listener
        swipeButton.addEventListener('touchend', () => {
            if (isSwiping) {
                isSwiping = false;
                swipeButton.style.transform = 'translateX(0px)'; // Reset button position

                // Perform an AJAX POST request to the server (get_entry.php)
                fetch('get_entry.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ message: 'Button swiped' }),
                })
                .then((response) => response.json())
                .then((data) => {
                    // Handle the response from the server here
                    console.log('Response from server:', data);
                })
                .catch((error) => {
                    console.error('Error:', error);
                });
            }
        });
    </script>
</body>
</html>
