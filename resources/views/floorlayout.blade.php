@extends('layouts.master')

@section('content')

<!--Main Section Start-->
<!--Main Section Start-->
  <div class="wrapper home-section" id="full-width">
    <div class="container-fluid p-4">
      <div class="row">
        <div class="col-12 col-md-12 col-lg-12">
          <div class="main-heading">
            <h4>Floor Layout</h4>
            <select class="form-select w-auto ">
              <option>Select Floor</option>
              <option>First Floor</option>
              <option>Second Floor</option>
              <option>Third Floor</option>
            </select>
          </div>
        </div>
        <div class="col-12 col-md-12 col-lg-12">
          <div class="main-heading">
            <h4>Floor Layout</h4>
          </div>
        </div>
        <div class="col-12 col-md-2 col-lg-2">
          <div class="floor_layout_setup">
            <div class="tab_one">
              <a href="#" data-bs-toggle="modal" data-bs-target="#addRole" data-bs-whatever="@mdo">
                <h5>Layout</h5>
                <svg xmlns="http://www.w3.org/2000/svg" width="27.2" height="27.2" viewBox="0 0 27.2 27.2">
                  <path id="layout-6-svgrepo-com"
                    d="M4,12.125V26.75A3.25,3.25,0,0,0,7.25,30h4.875M4,12.125V7.25A3.25,3.25,0,0,1,7.25,4h19.5A3.25,3.25,0,0,1,30,7.25v4.875m-26,0h8.125m17.875,0H12.125m17.875,0v8.938M12.125,12.125v8.938m0,8.937H26.75A3.25,3.25,0,0,0,30,26.75V21.063M12.125,30V21.063m0,0H30"
                    transform="translate(-3.4 -3.4)" fill="none" stroke="#474747" stroke-linecap="round"
                    stroke-linejoin="round" stroke-width="1.2" />
                </svg>
              </a>
            </div>
            <div class="tab_two">
              <a href="#" data-bs-toggle="modal" data-bs-target="#addtable" data-bs-whatever="@mdo">
                <h5>Tables</h5>
                <svg xmlns="http://www.w3.org/2000/svg" width="32.824" height="23.484" viewBox="0 0 32.824 23.484">
                  <path id="table-svgrepo-com"
                    d="M6.941,11l-6.7,6.145a.632.632,0,0,0-.126.115L0,17.374v.148a.553.553,0,0,0,0,.2v3.518H1.964V34.484H5.9V21.237H7.213v9.032H11.15V21.237h10.5v9.032h3.937V21.237H26.9V34.484h3.937V21.237H32.8V17.725a.553.553,0,0,0,0-.2v-.154l-.127-.116a.631.631,0,0,0-.12-.112l0,0L25.856,11H6.941Zm.543,1.2H25.313l5.249,4.817H2.235ZM1.308,18.226H31.49v1.806h-1.2a.715.715,0,0,0-.214,0H27.654a.645.645,0,0,0-.206,0H25.039a.714.714,0,0,0-.214,0H22.405a.645.645,0,0,0-.206,0H10.6a.715.715,0,0,0-.214,0H7.97a.645.645,0,0,0-.206,0H5.355a.714.714,0,0,0-.214,0H2.721a.645.645,0,0,0-.206,0H1.308Zm1.968,3.011H4.588V33.279H3.276Zm5.249,0H9.837v7.828H8.525Zm14.435,0h1.312v7.828H22.96Zm5.249,0h1.312V33.279H28.209Z"
                    transform="translate(0.013 -11)" fill="#474747" />
                </svg>

              </a>
            </div>
            <div class="tab_three">
              <a href="#">
                <h5>Decoration</h5>
                <svg xmlns="http://www.w3.org/2000/svg" width="25.98" height="34.638" viewBox="0 0 25.98 34.638">
                  <g id="fountain-with-water-svgrepo-com" transform="translate(-63.984 -0.001)">
                    <path id="Path_151" data-name="Path 151"
                      d="M89.423,76.421H78.6V71.844a6.34,6.34,0,0,0,1.68-.556,2.433,2.433,0,0,0,1.567-2.06.547.547,0,0,0-.541-.553H77.515v-1.66h.541a.553.553,0,0,0,0-1.106h-.514a5.625,5.625,0,0,1,.172-.937.555.555,0,0,0-.376-.682,2.491,2.491,0,0,1-.728,0,.555.555,0,0,0-.376.682,5.628,5.628,0,0,1,.172.937h-.514a.553.553,0,0,0,0,1.106h.541v1.66H72.644a.547.547,0,0,0-.541.553,2.433,2.433,0,0,0,1.567,2.06,6.339,6.339,0,0,0,1.68.556v4.577H64.525a.547.547,0,0,0-.541.553c0,1.456,1.414,2.848,3.983,3.918A22.962,22.962,0,0,0,75.35,82.46v1.153H73.726a.553.553,0,0,0,0,1.106,1.1,1.1,0,0,1,1.082,1.106v6.085H72.915a.822.822,0,0,0-.812.83v1.936a.547.547,0,0,0,.541.553H81.3a.547.547,0,0,0,.541-.553V92.741a.822.822,0,0,0-.812-.83H79.139V85.826a1.1,1.1,0,0,1,1.083-1.106.553.553,0,0,0,0-1.106H78.6V82.46a22.961,22.961,0,0,0,7.383-1.567c2.568-1.071,3.983-2.462,3.983-3.918A.547.547,0,0,0,89.423,76.421ZM73.438,69.782H80.51a6.2,6.2,0,0,1-7.072,0Zm2.995,2.2c.179.011.359.017.541.017s.363-.006.541-.017v4.443H76.433Zm4.33,22.146H73.185V93.018h7.577Zm-2.706-8.3v6.085H75.892V85.826a2.236,2.236,0,0,0-.291-1.106h2.748A2.236,2.236,0,0,0,78.057,85.826Zm-.541-2.213H76.433V82.5q.27.005.541.005t.541-.005Zm8.057-3.745a24.9,24.9,0,0,1-17.2,0A6.116,6.116,0,0,1,65.2,77.527H88.745A6.117,6.117,0,0,1,85.572,79.868Z"
                      transform="translate(0 -60.592)" fill="#474747" />
                    <path id="Path_152" data-name="Path 152"
                      d="M110.007,6.456a.456.456,0,0,0,.262-.083,4.562,4.562,0,0,1,1.107-.569.458.458,0,1,0-.3-.864,5.478,5.478,0,0,0-1.329.683.458.458,0,0,0,.263.833Z"
                      transform="translate(-42.957 -4.633)" fill="#474747" />
                    <path id="Path_153" data-name="Path 153"
                      d="M214.259,19.377a4.607,4.607,0,0,1,.869.892.458.458,0,0,0,.736-.545,5.525,5.525,0,0,0-1.042-1.069.458.458,0,1,0-.563.722Z"
                      transform="translate(-139.798 -17.495)" fill="#474747" />
                    <path id="Path_154" data-name="Path 154"
                      d="M76.864,40.614a.457.457,0,0,0,.58-.288A4.559,4.559,0,0,1,78,39.213a.458.458,0,0,0-.757-.515,5.476,5.476,0,0,0-.665,1.337A.458.458,0,0,0,76.864,40.614Z"
                      transform="translate(-11.849 -36.293)" fill="#474747" />
                    <path id="Path_155" data-name="Path 155"
                      d="M162.716.917a4.576,4.576,0,0,1,1.228.2.458.458,0,1,0,.269-.875A5.491,5.491,0,0,0,162.74,0a.458.458,0,1,0-.023.915Z"
                      transform="translate(-91.519 0)" fill="#474747" />
                    <path id="Path_156" data-name="Path 156"
                      d="M72.444,204.583a.458.458,0,0,0,.458-.458v-.261a.458.458,0,1,0-.916,0v.261A.458.458,0,0,0,72.444,204.583Z"
                      transform="translate(-7.544 -189.555)" fill="#474747" />
                    <path id="Path_157" data-name="Path 157"
                      d="M72.444,149.7a.458.458,0,0,0,.458-.458v-1.374a.458.458,0,0,0-.916,0v1.374A.458.458,0,0,0,72.444,149.7Z"
                      transform="translate(-7.544 -137.302)" fill="#474747" />
                    <path id="Path_158" data-name="Path 158"
                      d="M72.444,93.7a.458.458,0,0,0,.458-.458V91.864a.458.458,0,1,0-.916,0v1.374A.458.458,0,0,0,72.444,93.7Z"
                      transform="translate(-7.544 -85.14)" fill="#474747" />
                    <path id="Path_159" data-name="Path 159"
                      d="M424.9,147.864a.458.458,0,0,0-.916,0v1.374a.458.458,0,1,0,.916,0Z"
                      transform="translate(-335.395 -137.302)" fill="#474747" />
                    <path id="Path_160" data-name="Path 160"
                      d="M424.444,203.406a.458.458,0,0,0-.458.458v.261a.458.458,0,0,0,.916,0v-.261A.458.458,0,0,0,424.444,203.406Z"
                      transform="translate(-335.395 -189.555)" fill="#474747" />
                    <path id="Path_161" data-name="Path 161"
                      d="M423.986,91.864v1.374a.458.458,0,1,0,.916,0V91.864a.458.458,0,0,0-.916,0Z"
                      transform="translate(-335.395 -85.14)" fill="#474747" />
                    <path id="Path_162" data-name="Path 162"
                      d="M409.4,40.326a.458.458,0,0,0,.868-.292,5.476,5.476,0,0,0-.665-1.337.458.458,0,0,0-.757.515A4.56,4.56,0,0,1,409.4,40.326Z"
                      transform="translate(-321.051 -36.292)" fill="#474747" />
                    <path id="Path_163" data-name="Path 163"
                      d="M265.378,20.364a.458.458,0,0,0,.64-.1,4.606,4.606,0,0,1,.869-.892.458.458,0,0,0-.563-.722,5.524,5.524,0,0,0-1.042,1.069A.458.458,0,0,0,265.378,20.364Z"
                      transform="translate(-187.4 -17.495)" fill="#474747" />
                    <path id="Path_164" data-name="Path 164"
                      d="M365.448,5.8a4.564,4.564,0,0,1,1.107.569.458.458,0,0,0,.525-.75,5.481,5.481,0,0,0-1.329-.683.458.458,0,1,0-.3.864Z"
                      transform="translate(-279.919 -4.633)" fill="#474747" />
                    <path id="Path_165" data-name="Path 165"
                      d="M310.553,1.136a.457.457,0,0,0,.135-.02,4.578,4.578,0,0,1,1.228-.2A.458.458,0,1,0,311.892,0a5.491,5.491,0,0,0-1.474.24.458.458,0,0,0,.134.9Z"
                      transform="translate(-229.165 0)" fill="#474747" />
                  </g>
                </svg>


              </a>
            </div>
            <div class="tab_four">
              <a href="#">
                <h5>Billing Counter</h5>

                <svg xmlns="http://www.w3.org/2000/svg" width="38.054" height="37.111" viewBox="0 0 38.054 37.111">
                  <g id="i-billing-svgrepo-com" transform="translate(-0.796 -1.575)">
                    <path id="Path_167" data-name="Path 167"
                      d="M12.611,8.039a2.9,2.9,0,1,0-2.9-2.9,2.9,2.9,0,0,0,2.9,2.9Z"
                      transform="translate(-3.397 -0.088)" fill="none" stroke="#474747" stroke-width="0.9" />
                    <path id="Path_168" data-name="Path 168"
                      d="M48.883,7.91a2.943,2.943,0,1,0-2.942-2.943A2.944,2.944,0,0,0,48.883,7.91Z"
                      transform="translate(-17.928)" fill="none" stroke="#474747" stroke-width="0.9" />
                    <path id="Path_169" data-name="Path 169"
                      d="M8.145,39.458a6.9,6.9,0,1,0,6.9,6.9,6.9,6.9,0,0,0-6.9-6.9Zm.817,11.274v1.1H7.314v-1.1a3.354,3.354,0,0,1-2.88-2.969H6.191a1.4,1.4,0,0,0,1.138,1.3V47c-.379-.073-.591-.133-1-.244a2.325,2.325,0,0,1-1.758-2.277,2.939,2.939,0,0,1,2.741-2.564V40.883H8.963v1.029a3.038,3.038,0,0,1,2.847,2.83H10.088a1.206,1.206,0,0,0-1.125-1.149v1.938c.479.105.479.093.918.213a2.372,2.372,0,0,1,2.009,2.419c0,1.382-1.106,2.331-2.928,2.569Z"
                      transform="translate(-0.001 -15.015)" fill="none" stroke="#474747" stroke-width="0.9" />
                    <path id="Path_170" data-name="Path 170"
                      d="M10.746,48.009c-.737-.131-.941-.446-.941-.786,0-.4.5-.78.941-.831Zm1.668,3.829V50.091c.728.1,1.189.417,1.189.867,0,.4-.429.85-1.189.879Z"
                      transform="translate(-3.433 -17.796)" fill="none" stroke="#474747" stroke-width="0.9" />
                    <path id="Path_171" data-name="Path 171"
                      d="M5.625,17.61H6.767l-1.32,4.913h7.917l-1.32-4.913h1.142l1.353,4.913h2.547l-1.634-5.959a4.128,4.128,0,0,0-3.73-2.97H7.088a4.128,4.128,0,0,0-3.732,2.97L1.723,22.523H4.27L5.624,17.61Z"
                      transform="translate(-0.191 -4.64)" fill="none" stroke="#474747" stroke-width="0.9" />
                    <path id="Path_172" data-name="Path 172"
                      d="M34.1,13.545a2.618,2.618,0,0,1,2.619,2.617l0,8.033H38.4v3.293l-3.411,0V41.129a1.731,1.731,0,0,1-3.462,0V27.491h-1.1V41.129a1.731,1.731,0,0,1-3.462,0V27.492L1.246,27.5V24.2H26.965V16.46l-5.494,6.505a1.353,1.353,0,0,1-2.054-1.76l5.693-6.84a2.3,2.3,0,0,1,2.058-.82H34.1Z"
                      transform="translate(0 -4.621)" fill="none" stroke="#474747" stroke-width="0.9" />
                  </g>
                </svg>

              </a>
            </div>
            <div class="tab_five">
              <a href="#">
                <h5>Kitchen</h5>
                <svg id="kitchen-cabinets-svgrepo-com" xmlns="http://www.w3.org/2000/svg" width="33.508" height="27.834"
                  viewBox="0 0 33.508 27.834">
                  <path id="Path_173" data-name="Path 173"
                    d="M9.135,1.053H1.053v6.2H9.138l0-6.2ZM10.117,0H32.453a.9.9,0,0,1,.363.074.989.989,0,0,1,.308.207l.005.005a.937.937,0,0,1,.2.308.947.947,0,0,1,.074.363v6.4a.921.921,0,0,1-.074.365,1,1,0,0,1-.207.311l-.011.008a.96.96,0,0,1-.3.2.947.947,0,0,1-.363.074H23.465a.07.07,0,0,1-.071-.071V1.053H18.829V2.934l3.354,3.815a.528.528,0,0,1-.046.744l-.005,0a.492.492,0,0,1-.158.093.512.512,0,0,1-.18.033H11.718a.531.531,0,0,1-.526-.526.505.505,0,0,1,.046-.213.519.519,0,0,1,.128-.177l3.452-3.774V1.053H10.191V8.235a.07.07,0,0,1-.071.071H.954a.9.9,0,0,1-.363-.074,1,1,0,0,1-.311-.207.97.97,0,0,1-.207-.311A.876.876,0,0,1,0,7.355V.954A.921.921,0,0,1,.074.589.878.878,0,0,1,.281.281q0-.008.008-.008a.96.96,0,0,1,.3-.2A.921.921,0,0,1,.954,0ZM.954,14.27h31.6a.947.947,0,0,1,.363.074,1,1,0,0,1,.311.207l0,0a.969.969,0,0,1,.2.305.95.95,0,0,1,.074.365V26.857a.947.947,0,0,1-.074.363,1,1,0,0,1-.2.3l-.008.011,0,0a.909.909,0,0,1-.308.2.947.947,0,0,1-.363.074H23.994l-.068.016a.413.413,0,0,1-.082.005c-.027,0-.055,0-.082-.005a.277.277,0,0,1-.068-.016H9.814l-.068.016a.413.413,0,0,1-.082.005c-.027,0-.055,0-.082-.005a.277.277,0,0,1-.068-.016H.954a.9.9,0,0,1-.363-.074.93.93,0,0,1-.3-.2.021.021,0,0,1-.011-.011.989.989,0,0,1-.207-.308A.9.9,0,0,1,0,26.857V15.224a.921.921,0,0,1,.074-.365,1,1,0,0,1,.207-.311.038.038,0,0,1,.011-.008.96.96,0,0,1,.3-.2.945.945,0,0,1,.363-.071Zm31.5,1.053H24.371V26.759h8.085V15.323Zm-9.138,0H10.191V17.87H23.318V15.323Zm-14.18,0H1.053V26.759H9.138V15.323Zm1.053,11.434H23.318V18.925H10.191v7.832Zm7.586-25.7H15.871V3.131a.053.053,0,0,1,0,.019.537.537,0,0,1-.035.172.511.511,0,0,1-.1.164L12.909,6.569h7.717L17.919,3.49a.546.546,0,0,1-.106-.164.525.525,0,0,1-.038-.2V1.053Zm7.851,14.859a.563.563,0,0,1,.215.044l0,0a.578.578,0,0,1,.18.12.6.6,0,0,1,.123.183.563.563,0,0,1,.044.215.573.573,0,0,1-.044.215.6.6,0,0,1-.123.183l-.005,0a.57.57,0,0,1-.18.117.554.554,0,0,1-.431,0,.59.59,0,0,1-.177-.117l-.008-.005a.545.545,0,0,1-.12-.183l0,0a.547.547,0,0,1-.041-.213.563.563,0,0,1,.044-.215.553.553,0,0,1,.123-.183.6.6,0,0,1,.183-.123l0,0a.652.652,0,0,1,.215-.038ZM6.024,1.582a.563.563,0,0,1,.215.044.571.571,0,0,1,.305.305.554.554,0,0,1,0,.431.529.529,0,0,1-.117.177l-.008.008a.57.57,0,0,1-.18.117.563.563,0,0,1-.215.044.573.573,0,0,1-.215-.044.553.553,0,0,1-.183-.123L5.62,2.536A.59.59,0,0,1,5.5,2.359a.554.554,0,0,1,0-.431l0,0a.578.578,0,0,1,.12-.18.6.6,0,0,1,.183-.123l0,0a.7.7,0,0,1,.213-.038Zm18.459-.529h-.035v6.2h7.911v-6.2Zm2.509.69a.57.57,0,0,1,.18-.117.554.554,0,0,1,.431,0l0,0a.578.578,0,0,1,.18.12.6.6,0,0,1,.123.183.554.554,0,0,1,0,.431l0,0a.546.546,0,0,1-.117.175l-.008.008a.59.59,0,0,1-.177.117.554.554,0,0,1-.431,0,.553.553,0,0,1-.183-.123h0a.6.6,0,0,1-.123-.183.563.563,0,0,1-.044-.215.573.573,0,0,1,.044-.215.6.6,0,0,1,.123-.183l0,0ZM7.758,15.912a.563.563,0,0,1,.215.044l0,0a.63.63,0,0,1,.18.12l0,.005a.59.59,0,0,1,.117.177l0,0a.547.547,0,0,1,.041.213.573.573,0,0,1-.044.215.6.6,0,0,1-.123.183l-.005,0a.57.57,0,0,1-.18.117.554.554,0,0,1-.431,0,.553.553,0,0,1-.183-.123h0a.6.6,0,0,1-.123-.183l0,0a.547.547,0,0,1-.041-.213.563.563,0,0,1,.044-.215.553.553,0,0,1,.123-.183.6.6,0,0,1,.183-.123.688.688,0,0,1,.221-.041Z"
                    fill="#474747" />
                </svg>


              </a>
            </div>
            <div class="tab_six">
              <a href="#">
                <h5>Restroom</h5>

                <svg xmlns="http://www.w3.org/2000/svg" width="23.169" height="26.019" viewBox="0 0 23.169 26.019">
                  <path id="toilet-sign-svgrepo-com"
                    d="M30.061,26.019H12.388a.339.339,0,0,1-.327-.43l1.365-4.9a8.571,8.571,0,0,1-1.968-5.25h-.617A.339.339,0,0,1,10.5,15.1v-.237A1.46,1.46,0,0,1,11.962,13.4H23.718a1.461,1.461,0,0,1,1.442,1.24,1.081,1.081,0,0,0,.441-.423,1.105,1.105,0,0,0,.137-.553V3.833h-.266a.339.339,0,0,1-.339-.339V2.51a1.053,1.053,0,0,1,1.052-1.052h2.222a1.069,1.069,0,1,1,1.99,0H32.62A1.053,1.053,0,0,1,33.672,2.51v.984a.339.339,0,0,1-.339.339h-.266v8.881a4.309,4.309,0,0,1-1,2.766c-.106.136-.229.288-.351.439-.1.125-.2.249-.29.362s-.185.23-.282.348a6.029,6.029,0,0,0-1.624,3.555,23.583,23.583,0,0,0,.844,5.315l.025.081a.339.339,0,0,1-.325.438ZM12.834,25.34H29.62a21.187,21.187,0,0,1-.778-5.225A6.689,6.689,0,0,1,30.617,16.2c.094-.115.186-.227.274-.337s.192-.241.295-.368c.119-.148.24-.3.345-.431a3.665,3.665,0,0,0,.858-2.348V3.833h-5.97v9.829a1.783,1.783,0,0,1-.229.893,1.764,1.764,0,0,1-1.223.854.339.339,0,0,1-.128.025h-12.7a7.864,7.864,0,0,0,1.918,4.94v0l0,0a6.566,6.566,0,0,0,4.806,1.906.339.339,0,1,1,0,.678,7.406,7.406,0,0,1-4.892-1.723ZM11.792,14.756h12.7a.781.781,0,0,0-.774-.678H11.962a.781.781,0,0,0-.774.678Zm20.935-11.6h.266V2.51a.373.373,0,0,0-.373-.373H26.186a.374.374,0,0,0-.373.373v.645ZM29.4.678a.39.39,0,1,0,.39.39A.391.391,0,0,0,29.4.678Z"
                    transform="translate(-10.503)" fill="#474747" />
                </svg>

              </a>
            </div>
            <div class="tab_seven">
              <a href="#">
                <h5>Restroom</h5>

                <svg xmlns="http://www.w3.org/2000/svg" width="24.303" height="26.509" viewBox="0 0 24.303 26.509">
                  <path id="drink-svgrepo-com"
                    d="M26.875,5.437a.457.457,0,0,0-.323-.781H22.817L24.69,2.783A.906.906,0,1,0,24,1.914a.911.911,0,0,0,.045.223L21.525,4.656H3.164a.457.457,0,0,0-.323.781L14.4,17v9.6H9.83a.457.457,0,0,0-.457.457h0a.457.457,0,0,0,.457.457H19.886a.457.457,0,0,0,.457-.457h0a.457.457,0,0,0-.457-.457h-4.57V17ZM21.9,5.57h3.546l-.914.914H20.989Zm-17.636,0H20.61l-.914.914h-.054a2.014,2.014,0,0,0-1.171-.4,2.093,2.093,0,0,0-1.185.4H5.181ZM19.084,9.74a2.1,2.1,0,0,1-1.327.8,1.343,1.343,0,0,1-.935-.478c-.892-.892-.39-1.548.324-2.262A2.1,2.1,0,0,1,18.472,7a1.343,1.343,0,0,1,.935.478C20.3,8.369,19.8,9.026,19.084,9.74Zm-4.226,6.422L6.1,7.4H16.261a2.161,2.161,0,0,0-.41,2.931L13.03,13.151a.457.457,0,0,0,.646.646l2.816-2.816a2.057,2.057,0,0,0,1.265.474,2.885,2.885,0,0,0,1.974-1.069c.466-.466,1.612-1.615.775-2.986h3.114Z"
                    transform="translate(-2.707 -1)" fill="#474747" />
                </svg>


              </a>
            </div>
            <div class="tab_eight">
              <a href="#">
                <h5>Beverage </h5>


                <svg xmlns="http://www.w3.org/2000/svg" width="19.511" height="27.083" viewBox="0 0 19.511 27.083">
                  <g id="bubble_tea" data-name="bubble tea" transform="translate(-13.5 -0.5)">
                    <path id="Path_202" data-name="Path 202"
                      d="M27.883,16.68c.8.4,1.262.879,1.262,1.4,0,1.392-3.391,2.524-7.573,2.524S14,19.469,14,18.077c0-.517.463-1,1.262-1.4"
                      transform="translate(0 -9.084)" fill="none" stroke="#474747" stroke-miterlimit="10"
                      stroke-width="1" />
                    <path id="Path_203" data-name="Path 203"
                      d="M14,20v2.524c0,1.392,3.391,2.524,7.573,2.524s7.573-1.132,7.573-2.524V20"
                      transform="translate(0 -11.007)" fill="none" stroke="#474747" stroke-miterlimit="10"
                      stroke-width="1" />
                    <line id="Line_45" data-name="Line 45" x1="1.41" y1="12.689" transform="translate(14.887 12.984)"
                      fill="none" stroke="#474747" stroke-linecap="round" stroke-miterlimit="10" stroke-width="1" />
                    <line id="Line_46" data-name="Line 46" x1="1.41" y2="12.689" transform="translate(26.871 12.984)"
                      fill="none" stroke="#474747" stroke-linecap="round" stroke-miterlimit="10" stroke-width="1" />
                    <ellipse id="Ellipse_41" data-name="Ellipse 41" cx="5.287" cy="1.762" rx="5.287" ry="1.762"
                      transform="translate(16.297 23.558)" fill="none" stroke="#474747" stroke-miterlimit="10"
                      stroke-width="1" />
                    <path id="Path_204" data-name="Path 204"
                      d="M29.621,8.731c0,.93-2.827,1.683-6.31,1.683S17,9.661,17,8.731V8.31a6.31,6.31,0,0,1,12.621,0Z"
                      transform="translate(-1.738 -0.579)" fill="none" stroke="#474747" stroke-linecap="round"
                      stroke-miterlimit="10" stroke-width="1" />
                    <ellipse id="Ellipse_42" data-name="Ellipse 42" cx="1.057" cy="0.705" rx="1.057" ry="0.705"
                      transform="translate(23.346 4.525)" fill="#474747" />
                    <path id="Path_205" data-name="Path 205" d="M39,5.207l2.713-3.74A.841.841,0,0,1,42.467,1h4.527"
                      transform="translate(-14.483)" fill="none" stroke="#474747" stroke-linecap="round"
                      stroke-linejoin="round" stroke-width="1" />
                    <circle id="Ellipse_43" data-name="Ellipse 43" cx="1.057" cy="1.057" r="1.057"
                      transform="translate(23.346 17.214)" fill="none" stroke="#474747" stroke-miterlimit="10"
                      stroke-width="1" />
                    <circle id="Ellipse_44" data-name="Ellipse 44" cx="1.41" cy="1.41" r="1.41"
                      transform="translate(20.526 18.624)" fill="none" stroke="#474747" stroke-miterlimit="10"
                      stroke-width="1" />
                    <ellipse id="Ellipse_45" data-name="Ellipse 45" cx="0.705" cy="1.057" rx="0.705" ry="1.057"
                      transform="translate(24.756 20.034)" fill="none" stroke="#474747" stroke-miterlimit="10"
                      stroke-width="1" />
                    <ellipse id="Ellipse_46" data-name="Ellipse 46" cx="0.705" cy="1.057" rx="0.705" ry="1.057"
                      transform="translate(17.002 20.034)" fill="none" stroke="#474747" stroke-miterlimit="10"
                      stroke-width="1" />
                    <circle id="Ellipse_47" data-name="Ellipse 47" cx="1.41" cy="1.41" r="1.41"
                      transform="translate(17.707 17.214)" fill="none" stroke="#474747" stroke-miterlimit="10"
                      stroke-width="1" />
                    <line id="Line_47" data-name="Line 47" y1="0.705" x2="0.705" transform="translate(16.297 15.804)"
                      fill="none" stroke="#474747" stroke-linecap="round" stroke-linejoin="round" stroke-width="1" />
                    <line id="Line_48" data-name="Line 48" y2="0.705" transform="translate(21.231 15.804)" fill="none"
                      stroke="#474747" stroke-linecap="round" stroke-linejoin="round" stroke-width="1" />
                    <line id="Line_49" data-name="Line 49" x2="0.705" transform="translate(26.166 15.804)" fill="none"
                      stroke="#474747" stroke-linecap="round" stroke-linejoin="round" stroke-width="1" />
                  </g>
                </svg>


              </a>
            </div>
          </div>
        </div>
        <div class="col-12 col-md-10 col-lg-10">
          <div class="main-content p-3 position-relative" id="aWrapper">
            <canvas id="myCanvas" class="w-100">
              Your browser does not support the HTML canvas tag.</canvas>
            <canvas id="myCanvastwo"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!--Modal Popup Add start-->
  <div class="modal fade" id="addRole" tabindex="-1" aria-labelledby="addRoleLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addRoleLabel">Create Layout</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="mb-3 col-12 col-md-6 col-lg-6">
              <div class="row">
                <div class="mb-3 col-12 col-md-6 col-lg-6">
                  <label class="form-label" for="add_tax_class">Background Size</label>
                  <input type="text" placeholder="Height" id="add_height" class="form-control">
                </div>
                <div class="mb-3 col-12 col-md-6 col-lg-6">
                  <label class="form-label" for="add_tax_class">&nbsp;</label>
                  <input type="text" placeholder="Width" id="add_width" class="form-control">
                </div>
              </div>
            </div>
            <div class="mb-3 col-12 col-md-6 col-lg-6">
              <label class="form-label" for="add_tax_class">Background Color</label>
              <input type="color" id="add_background_color" class="form-control">
            </div>

            <div class="mb-3 col-12 col-md-6 col-lg-6">
              <label class="form-label" for="add_tax_class">Rows</label>
              <input type="text" placeholder="Enter number of rows" id="add_rows" class="form-control">
            </div>

            <div class="mb-3 col-12 col-md-6 col-lg-6">
              <label class="form-label" for="add_tax_class">Column</label>
              <input type="text" placeholder="Enter number of column" id="add_column" class="form-control">
            </div>
          </div>
        </div>
        <div class="modal-footer ">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-primary" onclick="addLayout()">Add</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="addtable" tabindex="-1" aria-labelledby="addtableLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addtableLabel">Create Layout</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="mb-3 col-12 col-md-6 col-lg-6">
              <label class="form-label" for="add_tax_class">postion X</label>
              <input type="text" id="pos1" class="form-control">
            </div>
            <div class="mb-3 col-12 col-md-6 col-lg-6">
              <label class="form-label" for="add_tax_class">postion Y</label>
              <input type="text" id="pos2" class="form-control">
            </div>
            <div class="mb-3 col-12 col-md-6 col-lg-6">
              <label class="form-label" for="add_tax_class">Table Height</label>
              <input type="text" id="table_height" class="form-control">
            </div>
            <div class="mb-3 col-12 col-md-6 col-lg-6">
              <label class="form-label" for="add_tax_class">Table Width</label>
              <input type="text" id="table_width" class="form-control">
            </div>
            <div class="mb-3 col-12 col-md-6 col-lg-6">
              <label class="form-label" for="add_tax_class">Tables</label>
              <input type="file" id="imgurl" onchange="handleFile(event)" class="form-control">
            </div>

          </div>
        </div>
        <div class="modal-footer ">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-primary" onclick="addTable()">Add</button>
        </div>
      </div>
    </div>
  </div>
  <!--Modal Popup Add end-->
  <!--Main Section End-->




@endsection
@section('js')

@endsection