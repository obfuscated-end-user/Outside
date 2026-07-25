import Layout from "../Layout";

export default function NotFound() {
	return (
		<Layout>
			<div className="p-8">
				<h1 className="text-4xl font-bold">404</h1>
				<p className="text-gray-500 dark:text-white">Page not found.</p><br/>
				<p title="Touch grass, AKA go outside.">
					Keyboard gathers dust<br/>
					Sunlight hits my confused face<br/>
					Grass wins. I log off.<br/>
				</p>
			</div>
		</Layout>
	);
}
