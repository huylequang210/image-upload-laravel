require('./bootstrap');
import {form, submitButton, saveImageToLocalStorage, imageContainer, getImagesLocalStorage, errorDiv} from './entry';
import Dropzone from './entry';
import {dropzoneOptionsFunction} from './entry';
import Axios from 'axios';
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
    // getImagesLocalStorage.forEach(arr => {
    //   addImagesToGrid(arr.map(el => isNaN(Number(el)) ? el : Number(el)), 2);
    // });
}

Dropzone.autoDiscover = false;

const dropzone = new Dropzone(form, dropzoneOptionsFunction(addImagesToGrid));

submitButton.addEventListener('click', function(e) {
  e.preventDefault();
  dropzone.processQueue();
});

const deleteImageForms = Array.from(document.querySelectorAll('.deleleImage'));
const publicStatusButtons = Array.from(document.querySelectorAll('.image-public'));
const privateStatusButtons = Array.from(document.querySelectorAll('.image-private'));
const editImageForms = Array.from(document.querySelectorAll('.editImage'));

function addInitialDeleteImagesEvent() {
  if(deleteImageForms) {
    deleteImageForms.forEach(deleteImageForm => {
      deleteImageForm.addEventListener('submit', function(e) {
        deleteImageEvent(e, deleteImageForm);
      });
    });
  } 
}
addInitialDeleteImagesEvent();

function addInitialStatusImagesEvent() {
  if(publicStatusButtons) {
    publicStatusButtons.forEach(publicStatusButton => {
      publicStatusButton.addEventListener('click', function(e) {
        statusImageEvent(e);
      });
    });
  }
  if(privateStatusButtons) {
    privateStatusButtons.forEach(privateStatusButton => {
      privateStatusButton.addEventListener('click', function(e) {
        statusImageEvent(e);
      });
    });
  }
}
addInitialStatusImagesEvent();

function addInitialTitleImagesEvent() {
  if(editImageForms) {
    editImageForms.forEach(editImageForm => {
      editImageForm.addEventListener('submit', function(e) {
        titleChangeEvent(e);
      });
    })
  }
}
addInitialTitleImagesEvent();

function addDeleteEventToNewImage(url) {
  const deleteImageForm = document.querySelector(`form[action='${url}']`);
  deleteImageForm.addEventListener('submit', function(e) {
    deleteImageEvent(e, deleteImageForm);
  });
}

function addStatusEventToNewImage(url) {
  const deleteImageForm = document.querySelector(`form[action='${url}']`);
  const imageVisibility = deleteImageForm.nextElementSibling;
  imageVisibility.firstElementChild.addEventListener('click', function(e) {
    statusImageEvent(e);
  });
  imageVisibility.lastElementChild.addEventListener('click', function(e) {
    statusImageEvent(e);
  });
}

function addTitleChangeEventToNewImage(url) {
  const editImageForm = document.querySelector(`.edit-title-name form[action='${url}']`);
  editImageForm.addEventListener('submit', function(e) {
    titleChangeEvent(e);
  })
}

// num:
// - 1: add to localStoage
// - 0: not add to localStoage
// response type: object
function addImagesToGrid(response = {}, num=0) {
  const div = `
  <div class="imageDiv mr-1 mb-4 sm:mb-1 h-350px sm:h-300px sm:w-210px flex flex-col items-center relative">
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
      <img class="girdImage" src="/images/${response.thumbnail}" alt="images">
    </a>
    <div class="image-info w-full h-65px bg-gray-900 text-white text-sm p-1 flex flex-col absolute bottom-100px sm:bottom-90px">
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
    <div class="image-edit h-full w-full bg-gray-900 text-white p-1 text-sm flex flex-col justify-around">
      <div class="image-status flex">
        <form action="/image/${response.id}"  method="POST" class="deleleImage block">
          <input type="hidden" name="_method" value="DELETE">                            
          <input type="hidden" name="_token" value=${document.getElementsByName('_token')[0].value}>
          <button class="deleteImageButton flex">Delete</button>
        </form>
        <div class="image-visibility flex">
          <button class="image-public flex ml-1 ${response.public_status == '1' ? 'status' : ''}">Public</button>
          <button class="image-private flex ml-1 ${response.public_status == 0 ? 'status' : ''}">Private</button>
        </div>
      </div>
      <div class="edit-title-name">
        <form action="/image/${response.id}" method="POST" class="editImage block">
          <input type="hidden" name="_method" value="PATCH">                            
          <input type="hidden" name="_token" value=${document.getElementsByName('_token')[0].value}>
          <input class="bg-gray-900 block w-full"
            type="tile" name="title" id="title" placeholder="Click here to edit title (50 max)" maxlength="50">
          <button type="submit" class="editImageButton block">Edit</button>
        </form>
      </div>
    </div>
  </div>
  <div class="deleted-placeholder justify-center items-center mr-1 mb-4 sm:mb-1 h-350px sm:h-300px sm:w-210px hide">
    <span class="font-bold">Move image to trash</span>
  </div>
`;

  imageContainer.insertAdjacentHTML("afterbegin", div);
  
  if(num === 1) {
    // convert number to string number, keep non-number
    // for(const value in response) {
    //   response[value] = isNaN(response[value]) ? response[value] : response[value] + '';
    // }

    addDeleteEventToNewImage(`/image/${response['id']}`);
    addStatusEventToNewImage(`/image/${response['id']}`);
    addTitleChangeEventToNewImage(`/image/${response['id']}`)
    saveImageToLocalStorage(1, response, 'images.home');
  }
}

// url: /images/randomstring.jpg
function removeImagesFromGrid(url) {
  const linkImage = document.querySelector(`a[data-original='${url}']`);
  const imageDiv = linkImage.parentElement;
  imageDiv.innerHTML = '';
  imageDiv.parentElement.removeChild(imageDiv);
}


async function deleteImageEvent(e, deleteImageForm) {
  e.preventDefault();
  if(!deleteImageForm) return;
  const deleteButton = deleteImageForm.querySelector('.deleteImageButton');
  deleteButton.innerHTML = '...';
  const res = await Axios.delete(`${deleteImageForm.action}`);
  if(res.status === 200) {
    // get placeholder
    const deletedImage = deleteImageForm.offsetParent.nextElementSibling;
    saveImageToLocalStorage(2, res.data, 'images.home');
    removeImagesFromGrid(`${res.data.original}`);
    deletedImage.style.display = "block";
  } else {
    deleteButton.innerHTML = 'Delete';
  }
}


async function statusImageEvent(e) {
  const button = e.target;
  const id = button.offsetParent.firstElementChild.dataset.id;
  const status = button.classList.contains('status');
  const sibling = button.classList.contains('image-public') ? 'next' : 'previous';
  const public_status = button.classList.contains('image-public') ? 1 : 0;
  if(status) return;
  else {
    const res = Axios.patch(`image/${id}`, {
      'public_status': public_status
    }).then(function(response) {
      button.classList.add('status');
      sibling === 'next' ? 
        button.nextElementSibling.classList.remove('status') : 
        button.previousElementSibling.classList.remove('status');
        saveImageToLocalStorage(3, response.data, 'images.home');
    }).catch(function(error) {
      console.log(error);
      errorDiv.innerHTML = 'Too many request, wait..';
    });
  }
}

async function titleChangeEvent(e) {
  e.preventDefault();
  const input = e.target.querySelector('input[id=title]');
  const res = Axios.patch(e.target.action, {
    'title': input.value
  }).then(function(response) {
    e.target.offsetParent.querySelector('.title').innerHTML = response.data.title;
    input.value = '';
    saveImageToLocalStorage(3, response.data, 'images.home');
  })
}