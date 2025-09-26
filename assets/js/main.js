function playAudio(src) {
	try {
		if (!sharedAudio) sharedAudio = new Audio();
		sharedAudio.pause();
		sharedAudio.currentTime = 0;
		sharedAudio.src = src;
		sharedAudio.load();
		sharedAudio.play().catch((e) => {
			console.warn('Cannot play audio', e);
		});
	} catch (e) {
		console.warn('Audio error', e);
	}
}
