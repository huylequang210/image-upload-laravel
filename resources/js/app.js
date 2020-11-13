require('./bootstrap');
import Dropzone from 'dropzone';

const form = document.querySelector('.dropzone');
const deleteImageForms = Array.from(document.querySelectorAll('.deleleImage'));
const submitButton = document.querySelector('#button');
const imageContainer = document.querySelector('.imageContainer');
let images = Array.from(document.querySelectorAll('.imageLink'));

// call when user click on back button or forward button
const perfEntries = performance.getEntriesByType('navigation');
if (perfEntries.length && perfEntries[0].type === 'back_forward') {
  isGridChanges();
}

// array format: [id, original url, thumbnail url]
let imagesObjectArray;
if(images) {
  imagesObjectArray = images.map(el => [el.dataset.id, el.dataset.original, el.dataset.thumbnail]);

}

// re-render images to avoid cache images
function isGridChanges() {
  let getImagesLocalStorage = JSON.parse(localStorage.getItem("images"));
  if(getImagesLocalStorage && getImagesLocalStorage.length > 0) {
    imageContainer.innerHTML = '';
    getImagesLocalStorage = getImagesLocalStorage.reverse();
    // convert string number to number, keep non-number
    getImagesLocalStorage.forEach(arr => {
      addImagesToGrid(arr.map(el => isNaN(Number(el)) ? el : Number(el)), 2);
    });
    localStorage.setItem('images', JSON.stringify(getImagesLocalStorage));
  }
}


Dropzone.autoDiscover = false;

const dropzoneOptions = {
  maxFilesize: 3,
  parallelUploads: 20,    
  maxFiles: 20,
  autoProcessQueue:false,
  addRemoveLinks: true,
  dictDefaultMessage: 'Drop Here!',
  success: function(file, response) {
    addImagesToGrid(response, 1);
    file.previewElement.innerHTML = "";
  },
  error: function(file, response) {
    console.log(response.message);
    if(file.previewElement) {
      let errorBar = file.previewElement.querySelector("[data-dz-errormessage]");
      if(response.message === "Unauthenticated.") {
        console.log('Hi');
        file.previewElement.innerHTML = "";
        file.previewElement.innerHTML = 'Please login to upload your images';
      } else if(response.message) {
        errorBar.innerHTML = "Unsupported image type<br>Only JPG, PNG, GIF or WebP files";
        // let progressBar = file.previewElement.querySelector("[data-dz-uploadprogress]");
        // progressBar.style.width = "50%";
        // progressBar.style.margin = "0 auto";
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
}

const dropzone = new Dropzone(form, dropzoneOptions);

submitButton.addEventListener('click', function(e) {
  e.preventDefault();
  dropzone.processQueue();
});

// add event for exist images
if(deleteImageForms) {
  deleteImageForms.forEach(deleteImageForm => {
    deleteImageForm.addEventListener('submit', function(e) {
      deleteImageEvent(e, deleteImageForm);
    });
  });
}

function addDeleteEventToNewImage(url) {
  const deleteImageForm = document.querySelector(`form[action='${url}']`);
  deleteImageForm.addEventListener('submit', function(e) {
    deleteImageEvent(e, deleteImageForm);
  });
}

// response: array:
// - 0: image id
// - 1: image url
// - 2: image_thumbnail url
// num:
// - 1: add to localStoage
// - 2: not add to localStoage
function addImagesToGrid(response, num) {
  const div =  `
    <div class="imageDiv mr-1 mb-1 w-210px h-210px flex justify-center relative">
      <a href="/images/${response[1]}" 
          class="block w-full h-full"
          data-original="${response[1]}
          data-thumbnail="${response[2]}">
        <img src="/images/${response[2]}" alt="images">
      </a>
      <form action="/api/images/${response[0]}" method="POST" class="deleleImage block absolute -bottom-20px invisible">
        <input type="hidden" name="_method" value="DELETE">                            
        <input type="hidden" name="_token" value=${document.getElementsByName('_token')[0].value}>
        <button class="deleteImageButton block text-white">Delete</button>
      </form>
    </div>
  `
  imageContainer.insertAdjacentHTML("afterbegin", div);
  addDeleteEventToNewImage(`/api/images/${response[0]}`);
  // convert number in array to string
  if(num === 1)
    saveImageToLocalStorage(1, response.map(String));
}

// url: /images/randomstring.jpg
function removeImagesFromGrid(url) {
  const linkImage = document.querySelector(`a[href='${url}']`);
  const imageDiv = linkImage.parentElement;
  imageDiv.innerHTML = '';
  imageDiv.parentElement.removeChild(imageDiv);
}


async function deleteImageEvent(e, deleteImageForm) {
  e.preventDefault();
  const deleteButton = deleteImageForm.querySelector('.deleteImageButton');
  deleteButton.innerHTML = 'Deleting...';
  const res = await fetch(`${deleteImageForm.action}`, {
    headers: {
      "X-CSRF-Token": document.getElementsByName('_token')[0].value,
      "X-Requested-With": "XMLHttpRequest"
    },
    method: "DELETE"
  });
  const data = await res.json();
  if(data) {
    saveImageToLocalStorage(2, data);
    removeImagesFromGrid(`/images/${data[1]}`);
  } else {
    deleteButton.textContent = 'Delete';
  }
}

// 1 - add, 2 - delete
function saveImageToLocalStorage(addOrDelete = 0, arr = []) {
  if(addOrDelete === 1 && arr.length > 0) {
    imagesObjectArray.unshift(arr);
  }
  if(addOrDelete === 2 && arr.length > 0) {
    imagesObjectArray = imagesObjectArray.filter((el, i) => 
      parseInt(el[0], 10) !== parseInt(arr[0],10));
  }
  localStorage.setItem('images', JSON.stringify(imagesObjectArray));
}