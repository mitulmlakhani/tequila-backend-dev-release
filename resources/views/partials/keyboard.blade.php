<div id="keyboardSection"
    style="position: fixed; bottom: 20px; left: 50%; transform: translateX(-30%); width: 100%; display: none; z-index: 1200;"
    tabindex="-1">

    <div class="keyboardContainer">
        <div class="simple-keyboard kb-custom-sec" style="width: 100%;">
            <div class="hg-row" style="display: flex; gap: 1em; width: 100%;">

                <p id="kb-dragHandle" style="cursor: move;" class="hg-button hg-standardBtn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="5" r="1"/><circle cx="12" cy="12" r="1"/><circle cx="12" cy="19" r="1"/><circle cx="5" cy="12" r="1"/><circle cx="19" cy="12" r="1"/><circle cx="7.8" cy="7.8" r="1"/><circle cx="16.2" cy="7.8" r="1"/><circle cx="7.8" cy="16.2" r="1"/><circle cx="16.2" cy="16.2" r="1"/></svg>
                </p>
                <button id="kb-disable" style="background: #dc3545;" class="hg-button hg-standardBtn">Disable</button>

                <div id="kb-clear" class="hg-button hg-standardBtn" data-skbtn="q" data-skbtnuid="default-r0b0">
                    <span>Clear</span>
                </div>

                <input disabled id="kb-preview" class="input" placeholder=""
                    style="border:0; width: 100%; height: 55px; margin-top: auto; margin-bottom: auto; font-size: 30px; padding-left: 10px;" />

                <div id="kb-backspace" class="hg-button hg-standardBtn" data-skbtn="{backspace}"
                    data-skbtnuid="default-r1b13">
                    <span>Backspace</span>
                </div>

                <button id="kb-hide" style="background: #dc3545;" class="hg-button hg-standardBtn">Close</button>

            </div>
        </div>
    </div>

    <div class="keyboardContainer">

        <div class="simple-keyboard-main">
        </div>

        <div class="controlArrows">
            <div class="simple-keyboard-control"></div>
            <div class="simple-keyboard-arrows"></div>
        </div>

        <div class="numPad">
            <div class="simple-keyboard-numpad"></div>
            <div class="simple-keyboard-numpadEnd" style="display: none;"></div>
        </div>

    </div>

</div>

<div id="kb-enable" style="color: red; display: none; z-index: 1200;">
    <img height="65px" src="{{ asset("assets/images/keyboard.png") }}" alt="Keyboard" />
</div>