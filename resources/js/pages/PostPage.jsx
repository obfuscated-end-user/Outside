import { useEffect, useState } from "react";
import { useParams, useNavigate } from "react-router-dom";

import axiosClient from "../api/axiosClient";
import { useAuth } from "../AuthContext";
import useConfirm from "../hooks/useConfirm";
import Layout from "../Layout";
import ConfirmModal from "../components/ConfirmModal";
import FeedPostCard from "../components/FeedPostCard";
import PostEditor from "../components/PostEditor";
import NotFound from "./NotFound";

export default function PostPage() {
	const { username, postId } = useParams();
	const navigate = useNavigate();
	const { user: currentUser } = useAuth();
	const [post, setPost] = useState(null);
	const [loading, setLoading] = useState(true);
	const [isEditing, setIsEditing] = useState(false);
	const [editBody, setEditBody] = useState("");
	const { confirmState, openConfirm, closeConfirm } = useConfirm();

	useEffect(() => {
		setLoading(true);
		axiosClient.get(`/api/posts/${postId}`)
			.then(res => setPost(res.data))
			.catch(() => setPost(null))
			.finally(() => setLoading(false));
	}, [postId]);

	if (loading) return <div className="p-8">Loading...</div>;
	if (!loading && !post) return <NotFound/>;

	return (
		<Layout>
			<FeedPostCard
				post={post} user={currentUser} navigate={navigate} isEditingAny={isEditing}
				setPosts={fn => {
						// adapt single post into array-like update
						setPost(prev => {
							const updated = fn([prev])[0];
							return updated;
						});
					}
				}
				onDelete={() => handleDelete()}
				onUpdate={(id, body) => { setEditBody(body); handleUpdate(); }}
				disableNavigation={true}
			/>
			<ConfirmModal
				show={confirmState.show} message={confirmState.message} onClose={closeConfirm}
				onConfirm={confirmState.onConfirm}
			/>
		</Layout>
	);
}
