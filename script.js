// script.js

document.addEventListener("DOMContentLoaded", () => {
    const careerForm = document.getElementById("career-form");
    if (careerForm) {
        const submitButton = document.getElementById("submit-button");
        const loadingIndicator = document.getElementById("loading-indicator");
        const formError = document.getElementById("form-error");

        careerForm.addEventListener("submit", async(e) => {
            e.preventDefault(); // Stop normal form submission

            // Show loading, hide error, disable button
            submitButton.disabled = true;
            submitButton.innerText = "Building...";
            loadingIndicator.style.display = "block";
            formError.style.display = "none";

            // Get form data
            const formData = new FormData(careerForm);

            try {
                // Send data to the backend using fetch
                const response = await fetch("get_suggestion.php", {
                    method: "POST",
                    body: formData,
                });

                if (response.ok) {
                    // Success! Backend will store result in session.
                    // Now, redirect to the result page
                    window.location.href = "result.php";
                } else {
                    // Handle HTTP errors (e.g., 401, 400, 500)
                    const errorData = await response.json();
                    let errorMessage = errorData.error || "An unknown error occurred.";

                    if (response.status === 401) {
                        errorMessage = "Your session expired. Please log in again.";
                        // Redirect to login after a delay
                        setTimeout(() => window.location.href = "login.php", 3000);
                    }

                    showError(errorMessage);
                }
            } catch (error) {
                // Handle network errors
                console.error("Fetch error:", error);
                showError("A network error occurred. Please try again.");
            }
        });

        function showError(message) {
            formError.innerText = message;
            formError.style.display = "block";
            submitButton.disabled = false;
            submitButton.innerText = "Build My Blueprint";
            loadingIndicator.style.display = "none";
        }
    }
});