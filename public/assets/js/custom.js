
$(document).ready(function () {
  $('.timezone_select').select2({
      placeholder: "Select Timezone",
      allowClear: true
  });
});

// $(document).ready(function(){
//   $('.nav-links li').click(function(){
//     $('li').removeClass("showMenu");

//     $(this).addClass("showMenu");
// });
// });



$(function ($) {
  var path = window.location.href; // because the 'href' property of the DOM element is the absolute path
  //console.log(path);
  $('.nav-links li a').each(function () {
    if (this.href === path || path.indexOf(this.href) != -1) {
      $(this).addClass('active');
      $(this).parent().parent().parent().addClass('showMenu');
    }
  });
});

$(document).ready(function() {
    $('.report-menu').find('.showMenu').each(function(e) {
        $(this).removeClass('showMenu');
    })
});

// $(document).ready(function () {
//     $(".report-menu li").hover(function () {
//         $("li").removeClass("showMenu");

//         $(this).addClass("showMenu");
//         $(".mainReportClass").removeClass(".showMenu");
//     });

//     $(".report-menu li").click(function () {
//       console.log('liiii', $(this));
//         $("li").removeClass("showMenu");

//         $(this).addClass("showMenu");
//         $(".mainReportClass").removeClass(".showMenu");
//     });

//     $(".bxs-chevron-right").on("click", function (e) {
//       e.preventDefault(); // Prevent default anchor or button behavior
//       $(".mainReportClass").removeClass(".showMenu");
//       console.log('iii', $(this)); // Logs the clicked element
//     });
// });




$(document).ready(function () {
  window.dataTable = $('#floormanagement, #staff-management,#restaurant-floormanagement, #category_management, #category_mapping, #category_variation, #combo_deals, #ingredients, #manage-table, #menu_category, #modifier, #permission-management, #role-managment, #role-permission-management, #stock_management, #tax_class, #tax_management, #waste_management, #management_reservation, #expense_type, #restaurant_management, #order_status, #payment_method, #customer_order, #vendor_invoice_management').DataTable({
    language: {
      paginate: {
        next: '<i class="fas fa-angle-right"></i>', // or '→'
        previous: '<i class="fas fa-angle-left"></i>' // or '←'
      }
    },
    "paging": true,
    "ordering": false,
    "searching": true,
    "lengthChange": true,

    scrollY: 'calc(100vh - 20px)',
    scrollCollapse: true,

    responsive: {
      details: {
        type: 'column',
      }
    },
    columnDefs: [{
      className: 'control',
      orderable: false,
      targets: 0
    }
    ],
    // order: [1, 'asc']
  });


  $('#item_management123').DataTable({
    language: {
      paginate: {
        next: '<i class="fas fa-angle-right"></i>', // or '→'
        previous: '<i class="fas fa-angle-left"></i>' // or '←'
      }
    },
    "paging": true,
    "ordering": true,
    "searching": true,
    "lengthChange": true,
    responsive: {
      details: {
        type: 'column',
      }
    },
    columnDefs: [{
      className: 'control',
      orderable: false,
      targets: 0
    },
    { responsivePriority: 1, targets: 0 },
    { responsivePriority: 2, targets: -1 },
    ],
    order: [1, 'asc']
  });
});


$('#item_management').DataTable({
  scrollY: '750px',
  scrollCollapse: true,
  // dom: 'Bfrtip',
  lengthMenu: [[-1, 10, 25, 50, 100], ['All', 10, 25, 50, 100]],
  select: {
    style: 'os',
    selector: 'td:first-child'
},
  fixedColumns: {
    left: 2,
    right: 1
  },
  language: {
    paginate: {
      next: '<i class="fas fa-angle-right"></i>', // or '→'
      previous: '<i class="fas fa-angle-left"></i>' // or '←'
    }
  },
  paging: false,
    scrollCollapse: true,
    scrollX: true,

  "paging": true,
  "ordering": false,
  "searching": true,
  "lengthChange": true,
  "sScrollX": "100%",
  "sScrollXInner": "110%",
  "bScrollCollapse": true,
});




$('#daily_sales_report, #hourly_sales_report').DataTable({
  dom: 'Bfrtip',
  select: {
    style: 'os',
    selector: 'td:first-child'
},
  fixedColumns: {
    left: 2,
    right: 1
  },
  language: {
    paginate: {
      next: '<i class="fas fa-angle-right"></i>', // or '→'
      previous: '<i class="fas fa-angle-left"></i>' // or '←'
    }
  },

  paging: false,
  ordering: false,
  searching: false,
  lengthChange: false,
  info: false, // Disable the information text
  scrollCollapse: true,
  scrollX: true,
  sScrollX: "100%",
  sScrollXInner: "110%",
  bScrollCollapse: false,
});

let arrow = document.querySelectorAll(".arrow");
for (var i = 0; i < arrow.length; i++) {
  arrow[i].addEventListener("click", (e) => {
    let arrowParent = e.target.parentElement.parentElement;//selecting main parent of arrow

    if (arrowParent.classList.contains("popupMenu")) {
      return;
    }

    arrowParent.classList.toggle("showMenu");
  });
}

let sidebar = document.querySelector(".sidebar");
let sidebarBtn = document.querySelector(".sidebar-toggle");
var wrapper = document.getElementById("full-width");
sidebarBtn?.addEventListener("click", () => {
  sidebar.classList.toggle("close");
  wrapper.classList.toggle("wrapper-left");

});
$(document).ready(function () {
  var checkall = document.getElementById("restaurantManageall");
  var checkadd = document.getElementById("restaurantManageAdd");
  var checkedit = document.getElementById("restaurantManageEdit");
  var checkdelete = document.getElementById("restaurantManageDelete");
  checkall?.addEventListener("click", () => {
    if (checkall.checked != false) {
      checkadd.checked = true;
      checkedit.checked = true;
      checkdelete.checked = true;
    } else if (checkall.checked != true) {
      checkadd.checked = false;
      checkedit.checked = false;
      checkdelete.checked = false;
    }
  })
})




var aWrapper = document.getElementById("aWrapper");
var canvas = document.getElementById("myCanvas");
var rowss = document.getElementById("add_rows");
var coloumnss = document.getElementById("add_column");
var bg_color = document.getElementById("add_background_color");
var add_height = document.getElementById("add_height");
var add_width = document.getElementById("add_width");
//Accesses the 2D rendering context for our canvasdfdf

var ctx = canvas?.getContext("2d");
function addLayout() {
  function setCanvasScalingFactor() {
    return window.devicePixelRatio || 1;
  }

  function resizeCanvas() {
    //Gets the devicePixelRatio
    var pixelRatio = setCanvasScalingFactor();

    //The viewport is in portrait mode, so var width should be based off viewport WIDTH
    if (window.innerHeight > window.innerWidth) {
      //Makes the canvas 100% of the viewport width
      var width = Math.round(1.0 * window.innerWidth);
    }
    //The viewport is in landscape mode, so var width should be based off viewport HEIGHT
    else {
      //Makes the canvas 100% of the viewport height
      var width = Math.round(1.0 * window.innerHeight);
    }

    //This is done in order to maintain the 1:1 aspect ratio, adjust as needed
    var height = width;

    //This will be used to downscale the canvas element when devicePixelRatio > 1
    canvas.style.width = add_width.value;
    canvas.style.height = add_height.value;

    canvas.width = width * pixelRatio;
    canvas.height = height * pixelRatio;
  }

  var cascadeFactor = 255;
  var cascadeCoefficient = 0;

  function draw() {
    //The number of color block columns and rows
    var columns = rowss.value;
    var rows = coloumnss.value;
    //The length of each square
    var length = Math.round(canvas.width / columns) - 2;

    //Increments or decrements cascadeFactor by 1, based on cascadeCoefficient
    cascadeFactor += cascadeCoefficient;

    //Makes sure the canvas is clean at the beginning of a frame
    ctx.clearRect(0, 0, canvas.width, canvas.height);

    for (var i = columns; i >= 1; i--) {
      for (var j = rows; j >= 1; j--) {
        //Where the color magic happens
        ctx.strokeStyle = "rgba(255, 0, 0, 0.5)";
        ctx.fillStyle = bg_color.value;
        ctx.fillRect((length * (i - 1)) + ((i - 1) * 2), (length * (j - 1)) + ((j - 1) * 2), length, length);
      }
    }
    if (cascadeFactor > 255 || cascadeFactor < 0) {
      //Resets the color cascade
      cascadeCoefficient = -cascadeCoefficient;
    }
    //Continuously calls draw() again until cancelled
    var aRequest = window.requestAnimationFrame(draw);
  }


  window.addEventListener("resize", resizeCanvas, false);

  resizeCanvas();
  draw();
}

var pos1 = document.getElementById("pos1");
var pos2 = document.getElementById("pos2");

function handleFile(event) {
  var files = event.currentTarget.files;
  var file = files[0];
  var canvas = document.getElementById('myCanvastwo');
  var ctx = canvas.getContext('2d');
  var fileReader = new FileReader();

  fileReader.onload = function (e) {
    var dataUrl = e.currentTarget.result;
    var image = new Image();
    image.onload = function () {
      ctx.drawImage(image, pos1.value, pos2.value, table_height.value, table_width.value);
    }

    image.src = dataUrl;
    console.log("the image is added")
  };
  fileReader.readAsDataURL(file);
}


// let subsubmenu = document.querySelector(".subsubmenu");

// for(var i=0; i< subsubmenu.length; i++ ){
//     subsubmenu[i].addEventListener("click", (e)=>{
//         let subarrowParent = e.target.parentElement.parentElement;//selecting main parent of arrow
//         subarrowParent.classList.toggle("test")
//     })}
function myFunction() {
  var table = document.getElementById("myTable");
  var row = table.insertRow(0);
  var cell1 = row.insertCell(0);
  var cell2 = row.insertCell(1);
  var cell3 = row.insertCell(2);
  var cell4 = row.insertCell(3);
  cell1.innerHTML = '<img src="/assets/images/dustbin.png" alt="dustbin" onclick="removeFun()">';
  cell2.innerHTML = '<input type="text" class="form-control">';
  cell3.innerHTML = '<input type="text" class="form-control">';
  cell4.innerHTML = '<input type="text" class="form-control">';
  cell5.innerHTML = '<input type="text" class="form-control">';
}

function removeFun() {
  var table = document.getElementById("myTable");
  table.deleteRow([]);
}

function myFunctionTwo() {
  var table = document.getElementById("variation_table");
  var row = table.insertRow(0);
  var cell1 = row.insertCell(0);
  var cell2 = row.insertCell(1);
  var cell3 = row.insertCell(2);
  var cell4 = row.insertCell(3);
  cell1.innerHTML = '<img src="/assets/images/dustbin.png" alt="dustbin" onclick="removeFunTwo()">';
  cell2.innerHTML = '<input type="text" class="form-control">';
  cell3.innerHTML = '<input type="text" class="form-control">';
  cell4.innerHTML = '<input type="text" class="form-control">';
}

function removeFunTwo() {
  var table = document.getElementById("variation_table");
  table.deleteRow([]);
}

function remove_tr(This) {
  if (This.closest('tbody').childElementCount == 0) {
    console.log("You Don't have Permission to Delete This ?");
  } else {
    This.closest('tr').remove();
  }
}


function formatPhone(obj) {
  var numbers = obj.value.replace(/\D/g, ''),
      char = {
          0: '(',
          3: ') ',
          6: '-'
      };
  obj.value = '';
  for (var i = 0; i < numbers.length; i++) {
      obj.value += (char[i] || '') + numbers[i];
  }

}

function showLoader(){
  $("#loading").css("z-index",99999999);
  $("#loading").removeClass("hideImg").addClass("showImg");
}

function hideLoader(){
  $("#loading").removeClass("showImg").addClass("hideImg");
}

