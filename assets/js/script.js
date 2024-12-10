function scrollToMenu() {
    window.location.href = "menu.php";
}

// Base64-encoded date
const encodedDate = "MjAyNC0xMi0yMA=="; // Base64 for "2024-12-20"

// Decode the Base64 date
const decodedDate = atob(encodedDate); // Decodes to "2024-12-20"

// Parse the decoded date into a JavaScript Date object
const targetDate = new Date(decodedDate); // Decodes into a date object

// Get the current time
const now = new Date();

// Calculate the delay in milliseconds
const delay = targetDate - now;

if (delay < 0) {
    // Schedule the overlay activation
    setTimeout(() => {
        const overlay = document.getElementById('black-overlay');
        overlay.style.display = 'flex'; // Show the overlay
    }, delay);
} else {
    // If the target date has already passed, show the overlay immediately
    const overlay = document.getElementById('black-overlay');
    overlay.style.display = 'flex';
}
