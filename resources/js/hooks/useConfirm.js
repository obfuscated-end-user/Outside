import { useState, useCallback } from "react";

export default function useConfirm() {
	const [confirmState, setConfirmState] = useState({
		show: false,
		message: "",
		onConfirm: null
	});

	const openConfirm = useCallback((message, onConfirm) => {
		setConfirmState({
			show: true,
			message,
			onConfirm
		});
	}, []);

	const closeConfirm = useCallback(() => {
		setConfirmState({
			show: false,
			message: "",
			onConfirm: null
		})
	}, []);

	return { confirmState, openConfirm, closeConfirm };
}
