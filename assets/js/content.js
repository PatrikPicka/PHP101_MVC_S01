let iframeIdentifier = 'content-video-iframe';

var tag = document.createElement('script');

tag.src = "https://www.youtube.com/iframe_api";
var firstScriptTag = document.getElementsByTagName('script')[0];
firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

var player;
function onYouTubeIframeAPIReady() {
	player = new YT.Player(iframeIdentifier, {
		height: '390',
		width: '640',
		videoId: youtubeVideoIdentifier,
		playerVars: {
			'playsinline': 1
		},
		events: {
			'onReady': onPlayerReady,
			'onStateChange': onPlayerStateChange
		}
	});
}

function onPlayerReady(event)
{
	updateUserLecture(1);
}

function onPlayerStateChange(event)
{
	let status = event.data;
	let userHasWatchedTheVideo = (status === 0 || event.target.getCurrentTime() >= event.target.getDuration() * 0.75);

	if (userHasWatchedTheVideo) {
		updateUserLecture(2);
	}
}

function updateUserLecture(status)
{
	$.post(updateUserLectureUrl, {
		userId: userId,
		contentId: contentId,
		status: status
	}).done((response) => {
		console.log(response);
		let responseData = JSON.parse(response);
		console.log(responseData);
	}).fail(() => {
		$(iframeIdentifier).remove();
	})
}