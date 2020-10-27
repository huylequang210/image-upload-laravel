require('./bootstrap');

import Dropzone from 'dropzone';
const form = document.querySelector('.dropzone');
const deleteImageForms = Array.from(document.querySelectorAll('.deleleImage'));
const submitButton = document.querySelector('#button');
const imageContainer = document.querySelector('.imageContainer');
Dropzone.autoDiscover = false;

const dropzoneOptions = {
  maxFilesize: 3,
  parallelUploads: 20,    
  maxFiles: 20,
  autoProcessQueue:false,
  addRemoveLinks: true,
  dictDefaultMessage: 'Drop Here!',
  success: function(file, response) {
    addImagesToGrid(response);
    // when user click on back button
    fetchImage();
  },
  error: function(file, response) {
    console.log(response);
  },
  uploadprogress: function(file, progress) {
    if(file.previewElement) {
      let progressBar = file.previewElement.querySelector("[data-dz-uploadprogress]");
      progressBar.style.width = progress + "%";
    }
  }
}

const dropzone = new Dropzone(form, dropzoneOptions);

submitButton.addEventListener('click', function(e) {
  e.preventDefault();
  dropzone.processQueue();
});

if(deleteImageForms) {
  deleteImageForms.forEach(deleteImageForm => {
    deleteImageForm.addEventListener('submit', function(e) {
      deleteImageEvent(e, deleteImageForm);
    });
  });
}

function addEventToNewImage(url) {
  const deleteImageForm = document.querySelector(`form[action='${url}']`);
  deleteImageForm.addEventListener('submit', function(e) {
    deleteImageEvent(e, deleteImageForm);
  });
}

async function fetchImage() {
  const res = await fetch(window.location.href);
}

function addImagesToGrid(response) {
  const div =  `
    <div class="imageDiv">
      <a href="/images/${response["imagesName"][0]}">
        <img src="/images/${response["imagesName"][1]}" alt="images">
      </a>
      <form action="/api/images/${response["imagesName"][2]}" method="POST" class="deleleImage">
        <input type="hidden" name="_method" value="DELETE">                            
        <input type="hidden" name="_token" value=${document.getElementsByName('_token')[0].value}>
        <button class="deleteImageButton">Delete</button>
      </form>
    </div>
  `
  imageContainer.insertAdjacentHTML("afterbegin", div);
  addEventToNewImage(`/api/images/${response["imagesName"][2]}`);
}

function removeImagesFromGrid(url) {
  const linkImage = document.querySelector(`a[href='${url}']`);
  const imageDiv = linkImage.parentElement;
  imageDiv.innerHTML = '';
  imageDiv.parentElement.removeChild(imageDiv);
}


async function deleteImageEvent(e, deleteImageForm) {
  e.preventDefault();
  const deleteButton = deleteImageForm.querySelector('.deleteImageButton');
  deleteButton.parentElement.removeChild(deleteButton);
  deleteImageForm.insertAdjacentHTML("afterend", "<span>Deleting...</span>")
  const res = await fetch(`${deleteImageForm.action}`, {
    headers: {
      "X-CSRF-Token": document.getElementsByName('_token')[0].value,
      "X-Requested-With": "XMLHttpRequest"
    },
    method: "DELETE"
  });
  const data = await res.json();
  if(data.ok) {
    removeImagesFromGrid(data.ok);
  } else {
    deleteButton.textContent = 'Delete';
  }
}