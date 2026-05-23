import axios from "axios";

/* const token = document
	.querySelector('meta[name="csrf-token"]')
	?.getAttribute("content"); */

// if (token) axiosClient.defaults.headers.common["X-CSRF-TOKEN"] = token;

// This creates a reusable Axios HTTP client for your React app to "talk" to Laravel.
const axiosClient = axios.create({
	baseURL: import.meta.env.VITE_API_URL || "http://127.0.0.1:8000",	// all requests go to Laravel server
	withCredentials: true,				// send/receive Laravel session cookies
	headers: {
		"X-Requested-With": "XMLHttpRequest",
		// "X-CSRF-TOKEN": token,
		Accept: "application/json"
	}
});

export default axiosClient;
