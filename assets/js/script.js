function scrollToMenu() {
    window.location.href = "menu.php";
}


// Base64-encoded date
const encodedDate = "MjAyNC0xMi0wOA=="; 

// Decode the Base64 date
const targetDate = new Date(atob(encodedDate)); 
// Get the current time
const now = new Date();

// Calculate the delay in milliseconds
const delay = targetDate - now;

if (delay > 0) {
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
