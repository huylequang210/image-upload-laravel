import {imageContainer, getImagesLocalStorage} from './entry';
import Axios from 'axios';

const galleryImage = document.querySelector('.gallery-image');
const imageHero = document.querySelector('.post-image-hero');
const deleteButtons = Array.from(document.querySelectorAll('.comment-delete'));
const updateButtons = Array.from(document.querySelectorAll('.comment-update'));
let score;

const upvoteButton = document.querySelector('.gallery-upvote');
const upvoteSVG = (upvoteButton !== null) && upvoteButton.querySelector('svg');
const downvoteButton = document.querySelector('.gallery-downvote');
const downvoteSVG = (downvoteButton !== null) && downvoteButton.querySelector('svg');
const points = document.querySelector('.gallery-points');
const pathname = window.location.pathname;
const id = pathname.slice(pathname.length - 1);

function renderBackForward() {
  Axios.get(`/upvote/${id}?t=${new Date().getTime()}`).then(function(response) {
    if(downvoteSVG.classList.contains("red") && response.data.score == "1") {
      downvoteSVG.classList.remove("red");
      upvoteSVG.classList.add("red");
      points.innerText = parseInt(points.innerText) + 2;
    } else if(response.data.score == "1") {
      upvoteSVG.classList.add("red");
    }
    if(upvoteSVG.classList.contains("red") && response.data.score == "-1") {
      upvoteSVG.classList.remove("red");
      downvoteSVG.classList.add("red");
      points.innerText = parseInt(points.innerText) - 2;
    } else if(response.data.score == "-1") {
      downvoteSVG.classList.add("red");
    }
    if(response.data.length === 0 && upvoteSVG.classList.contains("red")) {
      upvoteSVG.classList.remove("red");
      points.innerText = parseInt(points.innerText) - 1;
    }
    if(response.data.length === 0 && downvoteSVG.classList.contains("red")) {
      downvoteSVG.classList.remove("red");
      points.innerText = parseInt(points.innerText) + 1;
    }
    upvoteButton.style.opacity = "1";
    upvoteButton.style.visibility = "visible";
    downvoteButton.style.opacity = "1";
    downvoteButton.style.visibility = "visible";
  });
}

const perfEntries = performance.getEntriesByType('navigation');
if (perfEntries.length && perfEntries[0].type === 'back_forward') {
  upvoteButton.style.opacity = "0";
  upvoteButton.style.visibility = "hidden";
  downvoteButton.style.opacity = "0";
  downvoteButton.style.visibility = "hidden";
  renderBackForward();
}

function addDeleteEvent() {
  deleteButtons.forEach(deleteButton => {
    deleteButton.addEventListener('click', deleteButtonEvent);
  });
}

function addUpdateEvent() {
  updateButtons.forEach(updateButton => {
    updateButton.addEventListener('click', e => {
      createUpdateForm(e);
    });
  });
}
addUpdateEvent();

async function deleteButtonEvent(e) {
  let parent = e.target.offsetParent;
  let id = parseInt(parent.dataset.id);
  const res = await Axios.delete(`/comments/${id}`);
  if(res.status === 200) {
    location.reload();
  }
}
addDeleteEvent();
(upvoteButton !== null) && upvoteButton.addEventListener('click', e => {
  handleVote(1, upvoteSVG, downvoteSVG);
});

(upvoteButton !== null) && downvoteButton.addEventListener('click', e => {
  handleVote(-1, downvoteSVG, upvoteSVG);
});

function handleVote(score, voteSVG, otherSVG) {
  if(voteSVG.classList.contains("red")) {
    // delete
    Axios.delete(`/upvote/${id}/${score}`).then(function(response) {
      voteSVG.classList.remove("red");
      points.innerText = parseInt(points.innerText) - score;
    });
  } else if(otherSVG.classList.contains("red")) {
    // upvote
    Axios.patch(`/upvote/${id}/${score}`).then(function(response) {
      otherSVG.classList.remove("red");
      voteSVG.classList.add("red");
      points.innerText = parseInt(points.innerText) + score*2;
    });
  } else {
    // create
    Axios.post(`/upvote/${id}/${score}`).then(function(response) {
      voteSVG.classList.add("red");
      points.innerText = parseInt(points.innerText) + score;
    });
  }
}

function createUpdateForm(e) {
  let parent = e.target.offsetParent;
  let id = parseInt(parent.dataset.id);
  let exist = parent.querySelector('form');
  if(exist) return;
  let form = `
    <form action="/comments/${id}"  data-id=${id} method="POST" class="flex flex-col border-b-20 border-gray-500 relative items-end mb-4">
      <input type="hidden" name="_method" value="PATCH">                            
      <input type="hidden" name="_token" value=${document.getElementsByName('_token')[0].value}>
      <textarea name="body" id="comment" cols="47" rows="4"
        class="comment-area text-sm p-2 w-full" placeholder="comment here..."></textarea>
        <button type="submit" class="absolute mr-2 text-white font-bold -bottom-20px">Submit</button>
    </form> 
  `;
  parent.insertAdjacentHTML("afterbegin", form);
}

galleryImage.addEventListener('click', e => {
  e.stopPropagation();
  imageHero.style.visibility = 'visible';
  imageHero.style.opacity = "1";
}, false);

imageHero.addEventListener('click', el => {
  el.stopPropagation();
  imageHero.style.visibility = 'hidden';
  imageHero.style.opacity = "0";
}, false);

