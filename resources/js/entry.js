require('./bootstrap');

// for firefox fires js after back_forward
window.onunload = function(){};

import Dropzone from 'dropzone';

export default Dropzone;
export {dropzoneOptionsFunction};
export const form = document.querySelector('.dropzone');
export const submitButton = document.querySelector('#button');
export const imageContainer = document.querySelector('.imageContainer');
export let getImagesLocalStorage;
export const errorDiv = document.querySelector('.error');
let user_id = document.querySelector('.user-id').value;

let images = Array.from(document.querySelectorAll('.imageLink')).reverse();

function dropzoneOptionsFunction(func) {
  return {
    maxFilesize: 3,
    parallelUploads: 20,    
    maxFiles: 20,
    autoProcessQueue:false,
    addRemoveLinks: true,
    dictDefaultMessage: 'Drop Here!',
    success: function(file, response) {
      func(response, 1);
      file.previewElement.innerHTML = "";
    },
    error: function(file, response) {
      console.log(response);
      if(file.previewElement) {
        let errorBar = file.previewElement.querySelector("[data-dz-errormessage]");
        if(response.message === "Unauthenticated.") {
          file.previewElement.innerHTML = "";
          file.previewElement.innerHTML = 'Please login to upload your images';
        } else if(response.message) {
          errorBar.innerHTML = "Unsupported image type<br>Only JPG, PNG, GIF or WebP files";
        } else {
          errorBar.innerHTML = response;
        }
        errorBar.style.fontSize = "12px";
      }
      file.status = Dropzone.QUEUED;
    },
    uploadprogress: function(file, progress) {
      if(file.previewElement) {
        let progressBar = file.previewElement.querySelector("[data-dz-uploadprogress]");
        progressBar.style.width = progress + "px";
      }
    }
  };
}

let imagesObjectArray = images.map(el => {
  return {
    "id" : el.dataset.id,
    "original" : el.dataset.original,
    "thumbnail" : el.dataset.thumbnail,
    "title" : el.dataset.title,
    "public_status": el.dataset.public_status,
    "user_id": el.dataset.user_id,
    "view": el.dataset.view,
    "comments": el.dataset.comments,
    "upvote": el.dataset.upvote
  }
});

// call when user click on back button or forward button
const perfEntries = performance.getEntriesByType('navigation');
if(perfEntries.length && perfEntries[0].type === 'navigate' || perfEntries[0].type === 'reload') {
  addToLocalStorage();
}

if (perfEntries.length && perfEntries[0].type === 'back_forward') {
  console.log(perfEntries[0].type);
  isGridChanges();
}


// re-render images to avoid cache images
function isGridChanges() {
  if(window.location.pathname === "/home")
    getImagesLocalStorage = JSON.parse(localStorage.getItem("images.home"));
  else
    getImagesLocalStorage = JSON.parse(localStorage.getItem("images.welcome"));
  if(getImagesLocalStorage && getImagesLocalStorage.length > 0) {
    imageContainer.innerHTML = '';
  }
}

function addToLocalStorage() {
  let pathname = window.location.pathname;
  if(pathname === '/') 
    localStorage.setItem("images.welcome", JSON.stringify(imagesObjectArray));
  if(pathname === '/home') {
    localStorage.setItem("images.home", JSON.stringify(imagesObjectArray));
  }
}
  
// 1 - add, 2 - delete, 3 - put
export function saveImageToLocalStorage(action = 0, res, path) {
  let imagesHome = JSON.parse(localStorage.getItem('images.home'));
  let imagesWelcome = JSON.parse(localStorage.getItem('images.welcome'));
  // if null, set to empty array
  imagesHome ?? (imagesHome = []);
  imagesWelcome ?? (imagesWelcome = []);

  if(action === 1) [imagesHome, imagesWelcome] = addAction(imagesHome, imagesWelcome, res, path);
  if(action === 2) [imagesHome, imagesWelcome] = deleteAction(imagesHome, imagesWelcome, res);
  if(action === 3) [imagesHome, imagesWelcome] = editAction(imagesHome, imagesWelcome, res, path);

  localStorage.setItem('images.welcome', JSON.stringify(imagesWelcome));
  localStorage.setItem('images.home', JSON.stringify(imagesHome));
}

function addAction(imagesHome, imagesWelcome, res, path) {
  if(res && path === 'images.welcome') {
    imagesWelcome.push(res);
    if(res.user_id === user_id)
      imagesHome.push(res);
  }
  // automatically set to private
  if(res && path === 'images.home') {
    // do nothing
  }
  return [imagesHome, imagesWelcome];
}

function deleteAction(imagesHome, imagesWelcome, res) {
  if(Object.entries(res).length > 0) {
    imagesHome = imagesHome.filter(el => el.id !== res.id);
    // deleted image is public, remove image from welcome page
    if(parseInt(res.public_status) === 1) {
      imagesWelcome = imagesWelcome.filter((el, i) => {
        el.id !== res.id;
      });
    }
  }
  return [imagesHome, imagesWelcome];
}

function editAction(imagesHome, imagesWelcome, res, path) {
  if(res && path === 'images.home') {
    imagesHome.forEach(el => {
      if(parseInt(el.id) === res.id) {
        // if edit status to public
        if(res.public_status !== parseInt(el.public_status) && res.public_status === 1) {
          // get nearest element
          if(imagesWelcome.length === 0) {
            imagesWelcome.push(res);
            
          } else {
            const output = imagesWelcome.reduce((prev, curr) => {
              return Math.abs(parseInt(curr.id) - res.id) < Math.abs(parseInt(prev.id) - res.id) ? curr : prev;
            });
            // put public element to original place
            for(let i=0; i < imagesWelcome.length; i++) {
              if(parseInt(imagesWelcome[i].id) === parseInt(output.id)) {
                imagesWelcome.splice(i+1, 0, res);
                break;
              }
            }
          }
        }
        // if edit status to private
        if(res.public_status !== parseInt(el.public_status) && res.public_status === 0) {
          imagesWelcome = imagesWelcome.filter(el => parseInt(el.id) !== res.id);
        }
        el.title = res.title;
        el.public_status = res.public_status;
        el.comments = res.comments;
        el.upvote = res.upvote;
      }
    });
  }
  return [imagesHome, imagesWelcome];
}