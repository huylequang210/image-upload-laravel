require('./bootstrap');
import {form, submitButton, saveImageToLocalStorage, imageContainer, getImagesLocalStorage, b2_url} from './entry';
import {dropzoneOptionsFunction} from './entry';
import Dropzone from './entry';
// when user click back or forward button,
// re-render images via javascript
if(getImagesLocalStorage && imageContainer) {
    //convert string of number to number, keep non-number
    getImagesLocalStorage.map(el => {
      for(const value in el) {
        el[value] = isNaN(Number(el[value])) ? el[value] : Number(el[value]);
      }
      addImagesToGrid(el);
    });
}

Dropzone.autoDiscover = false;

const dropzone = new Dropzone(form, dropzoneOptionsFunction(addImagesToGrid));

submitButton.addEventListener('click', function(e) {
  e.preventDefault();
  dropzone.processQueue();
});

// num:
// - 1: add to localStoage
// - 2: not add to localStoage
// response type: object
function addImagesToGrid(response = {}, num) {
  if(!response.public_status) return;
  const div = `
    <div class="imageDiv mr-1 mb-1 sm:w-210px sm:h-210px flex flex-col items-center relative">
      <a href="/gallery/${response.id}" class="imageLink block w-full h-210" 
        data-id=${response.id}
        data-original=${response.original}
        data-thumbnail=${response.thumbnail}
        data-title="${response.title}"
        data-publicStatus=${response.public_status}
        data-view=${response.view}
        data-comments=${response.comments || "0"}
        data-upvote=${response.upvote}
        data-user_id="${response.user_id}">
        <img class="girdImage" src="${b2_url + response.thumbnail}" alt="images">
      </a>
      <div class="image-info w-full h-65px bg-gray-900 text-white text-sm p-1 flex flex-col absolute bottom-0">
        <div class="title flex-2"><p>${response.title}</p></div>
          <div class="image-item text-white flex-1 flex justify-around">
          <div class="image-item-upvote flex items-center">
          <span class="icon upvote-icon mr-0.1"></span>
          <span class="upvote-count text-sm">${response.upvote}</span>
          </div>
          <div class="image-item-comment flex items-center">
          <span class="icon comment-icon mr-0.1"></span>
          <span class="comment-count text-sm">${response.comments || "0"}</span>
          </div>
          <div class="image-item-seen flex items-center">
          <span class="icon seen-icon mr-0.1"></span>
          <span class="seen-count text-sm">${response.view}</span>
          </div>
        </div>
      </div>
    </div>
`;

  imageContainer.insertAdjacentHTML("afterbegin", div);

  
  if(num === 1) {
    ///convert number to string number, keep non-number
    for(const value in response) {
      response[value] = isNaN(response[value]) ? response[value] : response[value] + '';
    }
    saveImageToLocalStorage(1, response, 'images.welcome');
  }
}



