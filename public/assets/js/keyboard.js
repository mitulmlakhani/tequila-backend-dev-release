const Keyboard = window.SimpleKeyboard.default;

let activeInput = null;

// Common keyboard options
const commonKeyboardOptions = {
  onChange: handleInputChange,
  onKeyPress: handleKeyPress,
  theme: "simple-keyboard hg-theme-default hg-layout-default myTheme1",
  newLineOnEnter: true,
  physicalKeyboardHighlight: true,
  syncInstanceInputs: true,
  mergeDisplay: true,
  debug: false,
  preventMouseDownDefault: true,
  preventMouseUpDefault: false,
  stopMouseDownPropagation: true,
};

// Main keyboard
const keyboard = new Keyboard(".simple-keyboard-main", {
  ...commonKeyboardOptions,
  layout: {
    default: [
      "q w e r t y u i o p",
      "a s d f g h j k l +",
      "z x c v b n m - / *",
      "{capslock} .com @gmail.com @yahoo.com",
      "@ {space}",
    ],
    shift: [
      "Q W E R T Y U I O P",
      "A S D F G H J K L +",
      "Z X C V B N M - / *",
      "{capslock} .com @gmail.com @yahoo.com",
      "@ {space}",
    ],
  },
  display: {
    "{capslock}": "Caps Lock",
  },
});

new Keyboard(".simple-keyboard-numpad", {
  ...commonKeyboardOptions,
  layout: {
    default: [
      "{numpad1} {numpad2} {numpad3}",
      "{numpad4} {numpad5} {numpad6}",
      "{numpad7} {numpad8} {numpad9}",
      "{numpad0} {numpaddecimal}",
      "{numpadenter}",
    ],
  },
  display: {
    "{numpadenter}": "Enter",
  },
});

/**
 * Event listeners
 */
const inputElement = document.querySelector(".input");
if (inputElement) {
  inputElement.addEventListener("input", (event) => {
    keyboard.setInput(event.target.value);
  });
}

window.addEventListener("DOMContentLoaded", () => {
  bindButton("#kb-backspace", () => keyboard.handleButtonClicked("{backspace}"));
  bindButton("#kb-clear", clearKeyboard);

  if (localStorage.getItem("show-virtual-keyboard") === "N") {
    const kbEnable = document.getElementById("kb-enable");
    if (kbEnable) kbEnable.style.display = "block";
  }
});

/**
 * Helpers
 */
function handleInputChange(input) {
  const inputEl = document.querySelector(".input");
  const previewEl = document.getElementById("kb-preview");
  
  if (inputEl) inputEl.value = input;
  if (previewEl) previewEl.value = input;
  
  keyboard.setInput(input);
  
  if (activeInput) {
    activeInput.value = input;
    // Trigger custom events
    triggerEvent(activeInput, "input");
    triggerEvent(activeInput, "change");
  }
}

function handleKeyPress(button) {
  if (["{shift}", "{shiftleft}", "{shiftright}", "{capslock}"].includes(button)) {
    toggleShift();
  }
}

function toggleShift() {
  const layoutName = keyboard.options.layoutName === "default" ? "shift" : "default";
  keyboard.setOptions({ layoutName });
}

function clearKeyboard() {
  const clearBtn = document.getElementById("kb-clear");
  if (clearBtn) triggerEvent(clearBtn, "click");
  
  keyboard.setInput("");
  
  if (activeInput) {
    triggerEvent(activeInput, "input");
    activeInput.value = "";
  }
  
  const previewEl = document.getElementById("kb-preview");
  if (previewEl) previewEl.value = "";
}

function bindButton(selector, callback) {
  const btn = document.querySelector(selector);
  if (btn) {
    btn.addEventListener("mousedown", (e) => {
      e.preventDefault();
      e.stopPropagation();
    });
    btn.addEventListener("pointerdown", (e) => {
      e.preventDefault();
      e.stopPropagation();
    });
    btn.addEventListener("click", callback);
  } else {
    console.warn(`${selector} not found in DOM`);
  }
}

// Helper function to trigger events
function triggerEvent(element, eventType) {
  const event = new Event(eventType, { bubbles: true });
  element.dispatchEvent(event);
}

// Helper function to check if element matches selector (replaces jQuery .is())
function elementMatches(element, selector) {
  if (selector === '[readonly]') {
    return element.hasAttribute('readonly');
  }
  if (selector === ':disabled') {
    return element.disabled;
  }
  return element.matches ? element.matches(selector) : false;
}

// Helper function to show/hide elements
function showElement(element) {
  if (element) element.style.display = "block";
}

function hideElement(element) {
  if (element) element.style.display = "none";
}

// Focus event listener for inputs and textareas
document.addEventListener("focus", function(event) {
  const target = event.target;
  
  if ((target.tagName === "INPUT" || target.tagName === "TEXTAREA")
    && localStorage.getItem("show-virtual-keyboard") !== "N"
    ) {
    
    const ignoredTypes = ["checkbox", "radio", "hidden", "file", "button", "submit", "reset", "color", "date", "time", "datetime-local", "month", "week", "range"];
    const type = target.getAttribute("type");

    if (type && ignoredTypes.includes(type.toLowerCase())) {
      return;
    }

    if (elementMatches(target, '[readonly]') || elementMatches(target, ':disabled')) {
      return;
    }

    activeInput = target;

    keyboard.setInput(target.value);
    const previewEl = document.getElementById("kb-preview");
    if (previewEl) previewEl.value = target.value;

    const keyboardSection = document.getElementById("keyboardSection");
    showElement(keyboardSection);

    // Add blur event listener
    const blurHandler = function(e) {
      if (e && e.relatedTarget && e.relatedTarget.closest && e.relatedTarget.closest("#keyboardSection")) {
        return;
      }
      // Delay slightly to check if focus moved to or active interaction was on the onscreen keyboard
      setTimeout(() => {
        if (document.activeElement && document.activeElement.closest && document.activeElement.closest("#keyboardSection")) {
          return;
        }
        if (activeInput === target && document.activeElement !== target) {
          hideElement(keyboardSection);
          activeInput = null;
          target.removeEventListener("blur", blurHandler);
        }
      }, 100);
    };
    
    target.addEventListener("blur", blurHandler);
  }
}, true);

// Hide keyboard button
const kbHide = document.getElementById("kb-hide");
if (kbHide) {
  kbHide.addEventListener("click", function() {
    const keyboardSection = document.getElementById("keyboardSection");
    const kbEnable = document.getElementById("kb-enable");
    hideElement(keyboardSection);
    showElement(kbEnable);
    if (activeInput) {
      triggerEvent(activeInput, "blur");
    }
  });
}

// Disable keyboard button
const kbDisable = document.getElementById("kb-disable");
if (kbDisable) {
  kbDisable.addEventListener("click", function() {
    localStorage.setItem("show-virtual-keyboard", "N");
    const keyboardSection = document.getElementById("keyboardSection");
    const kbEnable = document.getElementById("kb-enable");
    hideElement(keyboardSection);
    showElement(kbEnable);
  });
}

// Prevent focus stealing / blur when clicking anywhere inside keyboard section
const keyboardSection = document.getElementById("keyboardSection");
if (keyboardSection) {
  ["mousedown", "pointerdown", "touchstart"].forEach((eventType) => {
    keyboardSection.addEventListener(eventType, function(e) {
      e.preventDefault();
      e.stopPropagation();
    });
  });
}

// Enable keyboard button
const kbEnable = document.getElementById("kb-enable");
if (kbEnable) {
  kbEnable.addEventListener("click", function() {
    localStorage.setItem("show-virtual-keyboard", "Y");
    const keyboardSection = document.getElementById("keyboardSection");
    showElement(keyboardSection);
    hideElement(kbEnable);
  });
}
