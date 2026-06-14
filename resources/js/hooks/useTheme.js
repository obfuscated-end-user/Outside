import { useEffect, useState } from "react";

export default function useTheme() {
	const getTheme = () => {
		const saved = localStorage.getItem("theme");
		if (saved) return saved;

		return window.matchMedia("(prefers-color-scheme: dark)").matches ?"dark" : "light";
	}

	const [theme, setTheme] = useState(getTheme);

	useEffect(() => {
		document.documentElement.classList.toggle("dark", theme === "dark");
		localStorage.setItem("theme", theme);
	}, [theme]);

	return {
		theme,
		toggleTheme: () => setTheme(t => (t === "dark" ? "light": "dark")),
	};
}