import axios from "axios";

// This creates a reusable Axios HTTP client for your React app to "talk" to Laravel.
const axiosClient = axios.create({
	// all requests go to Laravel server
	baseURL: import.meta.env.VITE_API_URL || "http://127.0.0.1:8000",
	// send/receive Laravel session cookies
	withCredentials: true,
	headers: {
		"X-Requested-With": "XMLHttpRequest",
		Accept: "application/json"
	}
});

export default axiosClient;
