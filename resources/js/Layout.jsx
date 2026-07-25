import { Link } from "react-router-dom";

import { useAuth } from "./AuthContext";
import useTheme from "./hooks/useTheme";

export default function Layout({ children }) {
	const { user, logout } = useAuth();
	const { theme, toggleTheme } = useTheme();

	// Some of the links don't work yet.
	return (
		<div className="min-h-screen bg-gray-100 flex justify-center dark:bg-gray-900 dark:text-white">
			{/* left sidebar */}
			<div className="w-64 bg-white dark:bg-gray-800 border-r p-4 flex flex-col sticky top-0 h-screen">
				<h1 className="text-xl font-bold mb-6">Outside</h1>
				<p className="font-bold mb-6 text-gray-400">Time to go outside, I guess.</p>
				<nav className="space-y-2">
					<Link to="/" className="block hover:underline cursor-pointer">Home</Link>
					<Link to={`/u/${user?.name}`} className="block hover:underline cursor-pointer">Profile</Link>
					<Link className="block hover:underline cursor-pointer">Settings</Link>
					<Link className="block hover:underline cursor-pointer">New post</Link>
					<button onClick={toggleTheme} className="py-1 cursor-pointer hover:underline">
						{ theme === "dark" ? "Dark" : "Light" }
					</button>
					<button onClick={logout} className="block text-left w-full hover:underline cursor-pointer text-red-600">
						Log out
					</button>
				</nav>
				{/* push footer down */}
				<div className="mt-auto text-sm text-gray-500 dark:text-gray-400">
					© {new Date().getFullYear()} random programmers incorporated.
				</div>
			</div>
			{/* main scrollable content */}
			<div className="w-full max-w-3xl p-8">{children}</div>
			{/* right sidebar */}
			<div className="w-64 bg-white dark:bg-gray-800 border-l p-4 sticky top-0 h-screen">
				<h2 className="font-semibold mb-4">Trending</h2>
				<p className="text-sm text-gray-500 dark:text-gray-400">#comingsoon</p>
				<p className="text-sm text-gray-500 dark:text-gray-400">#nothing</p>
				<p className="text-sm text-gray-500 dark:text-gray-400">#hashtag</p>
				<p className="text-sm text-gray-500 dark:text-gray-400">#なんちゅうファンキー！！</p>
				<p className="text-sm text-gray-500 dark:text-gray-400">#颟秸咳穜霦㠣㚬㒏㝷䦕𧤈𧬟𩔝𦩆𤲆</p>
				<p className="text-sm text-gray-500 dark:text-gray-400">#KINGSLAYER</p>
				<p className="text-sm text-gray-500 dark:text-gray-400">#þeodcyningaþrymgefrunon</p>
				<p className="text-sm text-gray-500 dark:text-gray-400">#MeThree</p>
			</div>
		</div>
	);
}
