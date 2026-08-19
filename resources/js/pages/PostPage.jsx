import { useEffect, useState } from "react";
import { useParams, useNavigate } from "react-router-dom";
import axiosClient from "../api/axiosClient";
import { useAuth } from "../AuthContext";
import useConfirm from "../hooks/useConfirm";
import Layout from "../Layout";
import ConfirmModal from "../components/ConfirmModal";
import FeedPostCard from "../components/FeedPostCard";
import NotFound from "./NotFound";

export default function PostPage() {
	const { postId } = useParams();
	const navigate = useNavigate();
	const { user: currentUser } = useAuth();
	const [post, setPost] = useState(null);
	const [loading, setLoading] = useState(true);
	const { confirmState, openConfirm, closeConfirm } = useConfirm();

	useEffect(() => {
		setLoading(true);
		axiosClient.get(`/api/posts/${postId}`)
			.then(res => { setPost({ ...res.data, isEditing: false, editBody: "" }); })
			.catch(() => { setPost(null); })
			.finally(() => { setLoading(false); });
	}, [postId]);

	const handleDelete = () => {
		if (!post) return;

		openConfirm(
			"Delete this post?",
			async () => {
				try {
					await axiosClient.delete(`/api/posts/${post.id}`);
					closeConfirm();
					navigate("/");
				} catch (error) {
					console.error("Failed to delete post:", error);
					alert("Failed to delete post.");
				}
			}
		);
	};

	const handleUpdate = (id, body) => {
		if (!body.trim()) return;

		openConfirm(
			"Save changes to this post?",
			async () => {
				try {
					const res = await axiosClient.put(`/api/posts/${id}`, { body });
					setPost({ ...res.data, isEditing: false, editBody: "" });
					closeConfirm();
				} catch (error) {
					console.error("Failed to update post:", error);
					alert("Failed to update post.");
				}
			}
		);
	};

	if (loading) return <div className="p-8">Loading...</div>;
	if (!post) return <NotFound />;

	return (
		<Layout>
			<FeedPostCard
				post={post}
				user={currentUser}
				navigate={navigate}
				isEditingAny={post.isEditing}
				setPosts={fn => {
					// adapt single post into array-like update
					setPost(prev => {
						const updated = fn([prev])[0];
						return updated;
					});
				}}
				onDelete={handleDelete}
				onUpdate={handleUpdate}
				disableNavigation={true}
			/>

			<ConfirmModal
				show={confirmState.show}
				message={confirmState.message}
				onClose={closeConfirm}
				onConfirm={confirmState.onConfirm}
			/>
		</Layout>
	);
}