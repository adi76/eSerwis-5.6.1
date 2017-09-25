/* ********************************************************************
 **********************************************************************
 * HTML Virtual Keyboard Interface Script - v1.38
 *   Copyright (c) 2010 - GreyWyvern
 *
 *  - Licenced for free distribution under the BSDL
 *          http://www.opensource.org/licenses/bsd-license.php
 *
 * Add a script-driven keyboard interface to text fields, password
 * fields and textareas.
 *
 * See http://www.greywyvern.com/code/javascript/keyboard for examples
 * and usage instructions.
 *
 * Version 1.38 - August 19, 2010
 *   - Restore correct WebKit detection
 *   - Fix for uncloseable keyboard in IE8
 *   - Prevent outside width and height CSS rules from bleeding into keyboard
 *   - Tightened rules for readonly inputs, fixes WebKit Bksp issue
 *
 *   See full changelog at:
 *     http://www.greywyvern.com/code/javascript/keyboard.changelog.txt
 *
 * Keyboard Credits
 *   - Polish Programmers layout by moose
 */
var VKI_attach, VKI_close;
  function VKI_buildKeyboardInputs() {
    var self = this;

    this.VKI_version = "1.38";
    this.VKI_showVersion = false;
    this.VKI_target = false;
    this.VKI_shift = this.VKI_shiftlock = false;
    this.VKI_altgr = this.VKI_altgrlock = false;
    this.VKI_dead = false;
    this.VKI_deadkeysOn = false;
    this.VKI_kts = this.VKI_kt = "Polish Prog";  // Default keyboard layout
    this.VKI_langAdapt = true;  // Use lang attribute of input to select keyboard
    this.VKI_size = 2;  // Default keyboard size (1-5)
    this.VKI_sizeAdj = true;  // Allow user to adjust keyboard size
    this.VKI_clearPasswords = true;  // Clear password fields on focus
    this.VKI_imageURI = "js/VirtualKeyboard/keyboard.png";  // If empty string, use imageless mode
    this.VKI_clickless = 0;  // 0 = disabled, > 0 = delay in ms
    this.VKI_keyCenter = 3;

    this.VKI_isIE = /*@cc_on!@*/false;
    this.VKI_isIE6 = /*@if(@_jscript_version == 5.6)!@end@*/false;
    this.VKI_isIElt8 = /*@if(@_jscript_version < 5.8)!@end@*/false;
    this.VKI_isWebKit = RegExp("KHTML").test(navigator.userAgent);
    this.VKI_isOpera = RegExp("Opera").test(navigator.userAgent);
    this.VKI_isMoz = (!this.VKI_isWebKit && navigator.product == "Gecko");


    /* ***** i18n text strings ************************************* */
    this.VKI_i18n = {
      '00': "Wirtualna klawiatura",
      '01': "Pokaż wirtualną klawiaturę",
      '02': "Wybierz wygl�d klawiatury",
      '03': "",
      '04': "On",
      '05': "Off",
      '06': "Wyłącz klawiaturę",
      '07': "Wyczyść",
      '08': "Wyczyść wybrane pole",
      '09': "Wersja",
      '10': "Rozmiar klawiatury"
    };


    /* ***** Create keyboards ************************************** */
    this.VKI_layout = {};

    // - Lay out each keyboard in rows of sub-arrays.  Each sub-array
    //   represents one key.
    //
    // - Each sub-array consists of four slots described as follows:
    //     example: ["a", "A", "\u00e1", "\u00c1"]
    //
    //          a) Normal character
    //          A) Character + Shift/Caps
    //     \u00e1) Character + Alt/AltGr/AltLk
    //     \u00c1) Character + Shift/Caps + Alt/AltGr/AltLk
    //
    //   You may include sub-arrays which are fewer than four slots.
    //   In these cases, the missing slots will be blanked when the
    //   corresponding modifier key (Shift or AltGr) is pressed.
    //
    // - If the second slot of a sub-array matches one of the following
    //   strings:
    //     "Tab", "Caps", "Shift", "Enter", "Bksp",
    //     "Alt" OR "AltGr", "AltLk"
    //   then the function of the key will be the following,
    //   respectively:
    //     - Insert a tab
    //     - Toggle Caps Lock (technically a Shift Lock)
    //     - Next entered character will be the shifted character
    //     - Insert a newline (textarea), or close the keyboard
    //     - Delete the previous character
    //     - Next entered character will be the alternate character
    //     - Toggle Alt/AltGr Lock
    //
    //   The first slot of this sub-array will be the text to display
    //   on the corresponding key.  This allows for easy localisation
    //   of key names.
    //
    // - Layout dead keys (diacritic + letter) should be added as
    //   arrays of two item arrays with hash keys equal to the
    //   diacritic.  See the "this.VKI_deadkey" object below the layout
    //   definitions.  In  each two item child array, the second item
    //   is what the diacritic would change the first item to.
    //
    // - To disable dead keys for a layout, simply assign true to the
    //   DDK property of the layout (DDK = disable dead keys).  See the
    //   Numpad layout below for an example.
    //
    // - Note that any characters beyond the normal ASCII set should be
    //   entered in escaped Unicode format.  (eg \u00a3 = Pound symbol)
    //   You can find Unicode values for characters here:
    //     http://unicode.org/charts/
    //
    // - To remove a keyboard, just delete it, or comment it out of the
    //   source code. If you decide to remove the US Int'l keyboard
    //   layout, make sure you change the default layout (this.VKI_kt)
    //   above so it references an existing layout.

    this.VKI_layout["Polish Prog"] = [ // Polish Programmers Keyboard
      [["`", "~"], ["1", "!"], ["2", "@"], ["3", "#"], ["4", "$"], ["5", "%"], ["6", "^"], ["7", "&"], ["8", "*"], ["9", "("], ["0", ")"], ["-", "_"], ["=", "+"], ["Bksp", "Bksp"]],
      [["Tab", "Tab"], ["q", "Q"], ["w", "W"], ["e", "E", "\u0119", "\u0118"], ["r", "R"], ["t", "T"], ["y", "Y"], ["u", "U"], ["i", "I"], ["o", "O", "\u00f3", "\u00d3"], ["p", "P"], ["[", "{"], ["]", "}"], ["\\", "|"]],
      [["Caps", "Caps"], ["a", "A", "\u0105", "\u0104"], ["s", "S", "\u015b", "\u015a"], ["d", "D"], ["f", "F"], ["g", "G"], ["h", "H"], ["j", "J"], ["k", "K"], ["l", "L", "\u0142", "\u0141"], [";", ":"], ["'", '"'], ["Enter", "Enter"]],
      [["Shift", "Shift"], ["z", "Z", "\u017c", "\u017b"], ["x", "X", "\u017a", "\u0179"], ["c", "C", "\u0107", "\u0106"], ["v", "V"], ["b", "B"], ["n", "N", "\u0144", "\u0143"], ["m", "M"], [",", "<"], [".", ">"], ["/", "?"], ["Shift", "Shift"]],
      [[" ", " ", " ", " "], ["Alt", "Alt"]]
    ]; this.VKI_layout["Polish Prog"].lang = ["pl"];

    /* ***** Define Dead Keys ************************************** */
    this.VKI_deadkey = {};

    // - Lay out each dead key set in one row of sub-arrays.  The rows
    //   below are wrapped so uppercase letters are below their
    //   lowercase equivalents.
    //
    // - The first letter in each sub-array is the letter pressed after
    //   the diacritic.  The second letter is the letter this key-combo
    //   will generate.
    //
    // - Note that if you have created a new keyboard layout and want
    //   it included in the distributed script, PLEASE TELL ME if you
    //   have added additional dead keys to the ones below.

    this.VKI_deadkey['"'] = this.VKI_deadkey['\u00a8'] = [ // Umlaut / Diaeresis / Greek Dialytika
      ["a", "\u00e4"], ["e", "\u00eb"], ["i", "\u00ef"], ["o", "\u00f6"], ["u", "\u00fc"], ["y", "\u00ff"], ["\u03b9", "\u03ca"], ["\u03c5", "\u03cb"], ["\u016B", "\u01D6"], ["\u00FA", "\u01D8"], ["\u01D4", "\u01DA"], ["\u00F9", "\u01DC"],
      ["A", "\u00c4"], ["E", "\u00cb"], ["I", "\u00cf"], ["O", "\u00d6"], ["U", "\u00dc"], ["Y", "\u0178"], ["\u0399", "\u03aa"], ["\u03a5", "\u03ab"], ["\u016A", "\u01D5"], ["\u00DA", "\u01D7"], ["\u01D3", "\u01D9"], ["\u00D9", "\u01DB"],
      ["\u304b", "\u304c"], ["\u304d", "\u304e"], ["\u304f", "\u3050"], ["\u3051", "\u3052"], ["\u3053", "\u3054"],
      ["\u305f", "\u3060"], ["\u3061", "\u3062"], ["\u3064", "\u3065"], ["\u3066", "\u3067"], ["\u3068", "\u3069"],
      ["\u3055", "\u3056"], ["\u3057", "\u3058"], ["\u3059", "\u305a"], ["\u305b", "\u305c"], ["\u305d", "\u305e"],
      ["\u306f", "\u3070"], ["\u3072", "\u3073"], ["\u3075", "\u3076"], ["\u3078", "\u3079"], ["\u307b", "\u307c"],
      ["\u30ab", "\u30ac"], ["\u30ad", "\u30ae"], ["\u30af", "\u30b0"], ["\u30b1", "\u30b2"], ["\u30b3", "\u30b4"],
      ["\u30bf", "\u30c0"], ["\u30c1", "\u30c2"], ["\u30c4", "\u30c5"], ["\u30c6", "\u30c7"], ["\u30c8", "\u30c9"],
      ["\u30b5", "\u30b6"], ["\u30b7", "\u30b8"], ["\u30b9", "\u30ba"], ["\u30bb", "\u30bc"], ["\u30bd", "\u30be"],
      ["\u30cf", "\u30d0"], ["\u30d2", "\u30d3"], ["\u30d5", "\u30d6"], ["\u30d8", "\u30d9"], ["\u30db", "\u30dc"]
    ];
    this.VKI_deadkey['~'] = [ // Tilde / Stroke
      ["a", "\u00e3"], ["l", "\u0142"], ["n", "\u00f1"], ["o", "\u00f5"],
      ["A", "\u00c3"], ["L", "\u0141"], ["N", "\u00d1"], ["O", "\u00d5"]
    ];
    this.VKI_deadkey['^'] = [ // Circumflex
      ["a", "\u00e2"], ["e", "\u00ea"], ["i", "\u00ee"], ["o", "\u00f4"], ["u", "\u00fb"], ["w", "\u0175"], ["y", "\u0177"],
      ["A", "\u00c2"], ["E", "\u00ca"], ["I", "\u00ce"], ["O", "\u00d4"], ["U", "\u00db"], ["W", "\u0174"], ["Y", "\u0176"]
    ];

    this.VKI_deadkey['\u02DB'] = [ // Ogonek
      ["a", "\u0106"], ["e", "\u0119"], ["i", "\u012f"], ["o", "\u01eb"], ["u", "\u0173"], ["y", "\u0177"],
      ["A", "\u0105"], ["E", "\u0118"], ["I", "\u012e"], ["O", "\u01ea"], ["U", "\u0172"], ["Y", "\u0176"]
    ];

    /* ***** Define Symbols **************************************** */
    this.VKI_symbol = {
      '\u200c': "ZW\r\nNJ", '\u200d': "ZW\r\nJ"
    };



    /* ****************************************************************
     * Attach the keyboard to an element
     *
     */
    this.VKI_attachKeyboard = VKI_attach = function(elem) {
      if (elem.VKI_attached) return false;
      if (this.VKI_imageURI) {
        var keybut = document.createElement('img');
            keybut.src = this.VKI_imageURI;
            keybut.alt = this.VKI_i18n['00'];
            keybut.className = "keyboardInputInitiator";
            keybut.title = this.VKI_i18n['01'];
            keybut.elem = elem;
            keybut.onclick = function() { self.VKI_show(this.elem); };
        elem.parentNode.insertBefore(keybut, (elem.dir == "rtl") ? elem : elem.nextSibling);
      } else elem.onfocus = function() { if (self.VKI_target != this) self.VKI_show(this); };
      elem.VKI_attached = true;
      if (this.VKI_isIE) {
        elem.onclick = elem.onselect = elem.onkeyup = function(e) {
          if ((e || event).type != "keyup" || !this.readOnly)
            this.range = document.selection.createRange();
        };
      }
    };


    /* ***** Find tagged input & textarea elements ***************** */
    var inputElems = [
      document.getElementsByTagName('input'),
      document.getElementsByTagName('textarea')
    ];
    for (var x = 0, elem; elem = inputElems[x++];)
      for (var y = 0, ex; ex = elem[y++];)
        if ((ex.nodeName == "TEXTAREA" || ex.type == "text" || ex.type == "password") && ex.className.indexOf("keyboardInput") > -1)
          this.VKI_attachKeyboard(ex);


    /* ***** Build the keyboard interface ************************** */
    this.VKI_keyboard = document.createElement('table');
    this.VKI_keyboard.id = "keyboardInputMaster";
    this.VKI_keyboard.dir = "ltr";
    this.VKI_keyboard.cellSpacing = this.VKI_keyboard.border = "0";

    var thead = document.createElement('thead');
      var tr = document.createElement('tr');
        var th = document.createElement('th');
          var abbr = document.createElement('abbr');
              abbr.title = this.VKI_i18n['00'];
              abbr.appendChild(document.createTextNode('Rozmiar'));
            th.appendChild(abbr);

     /*     var kblist = document.createElement('select');
              kblist.title = this.VKI_i18n['02'];
            for (ktype in this.VKI_layout) {
              if (typeof this.VKI_layout[ktype] == "object") {
                if (!this.VKI_layout[ktype].lang) this.VKI_layout[ktype].lang = [];
                var opt = document.createElement('option');
                    opt.value = ktype;
                    opt.appendChild(document.createTextNode(ktype));
                  kblist.appendChild(opt);
              }
            }
            if (kblist.options.length) {
                kblist.value = this.VKI_kt;
                kblist.onchange = function() {
                  self.VKI_kts = self.VKI_kt = this.value;
                  self.VKI_buildKeys();
                  self.VKI_position(true);
                };
              th.appendChild(kblist);
            }
*/
          if (this.VKI_sizeAdj) {
            this.VKI_size = Math.min(5, Math.max(1, this.VKI_size));
            var kbsize = document.createElement('select');
                kbsize.title = this.VKI_i18n['10'];
              for (var x = 1; x <= 5; x++) {
                var opt = document.createElement('option');
                    opt.value = x;
                    opt.appendChild(document.createTextNode(x));
                  kbsize.appendChild(opt);
              } kbsize.value = this.VKI_size;
                kbsize.change = function() {
                  self.VKI_size = this.value;
                  self.VKI_keyboard.className = self.VKI_keyboard.className.replace(/ ?keyboardInputSize\d ?/, "");
                  if (this.value != 2) self.VKI_keyboard.className += " keyboardInputSize" + this.value;
                  self.VKI_position(true);
                };
                kbsize.onchange = kbsize.change;
            th.appendChild(kbsize);
          }

           var label = document.createElement('label');
              var checkbox = document.createElement('input');
                  checkbox.type = "checkbox";
                  checkbox.title = this.VKI_i18n['03'] + ": " + ((this.VKI_deadkeysOn) ? this.VKI_i18n['04'] : this.VKI_i18n['05']);
                  checkbox.defaultChecked = this.VKI_deadkeysOn;
                  checkbox.onclick = function() {
                    self.VKI_deadkeysOn = this.checked;
                    this.title = self.VKI_i18n['03'] + ": " + ((this.checked) ? self.VKI_i18n['04'] : self.VKI_i18n['05']);
                    self.VKI_modify("");
                    return true;
                  };
                label.appendChild(this.VKI_deadkeysElem = checkbox);
                  checkbox.checked = this.VKI_deadkeysOn;
            th.appendChild(label);
          tr.appendChild(th);

        var td = document.createElement('td');
          var clearer = document.createElement('span');
              clearer.id = "keyboardInputClear";
              clearer.appendChild(document.createTextNode(this.VKI_i18n['07']));
              clearer.title = this.VKI_i18n['08'];
              clearer.onmousedown = function() { this.className = "pressed"; };
              clearer.onmouseup = function() { this.className = ""; };
              clearer.onclick = function() {
                self.VKI_target.value = "";
                self.VKI_target.focus();
                return false;
              };
            td.appendChild(clearer);

          var closer = document.createElement('strong');
              closer.id = "keyboardInputClose";
              closer.appendChild(document.createTextNode('X'));
              closer.title = this.VKI_i18n['06'];
              closer.onmousedown = function() { this.className = "pressed"; };
              closer.onmouseup = function() { this.className = ""; };
              closer.onclick = function() { self.VKI_close(); };
            td.appendChild(closer);

          tr.appendChild(td);
        thead.appendChild(tr);
    this.VKI_keyboard.appendChild(thead);

    var tbody = document.createElement('tbody');
      var tr = document.createElement('tr');
        var td = document.createElement('td');
            td.colSpan = "2";
          var div = document.createElement('div');
              div.id = "keyboardInputLayout";
            td.appendChild(div);
          if (this.VKI_showVersion) {
            var div = document.createElement('div');
              var ver = document.createElement('var');
                  ver.title = this.VKI_i18n['09'] + " " + this.VKI_version;
                  ver.appendChild(document.createTextNode("v" + this.VKI_version));
                div.appendChild(ver);
              td.appendChild(div);
          }
          tr.appendChild(td);
        tbody.appendChild(tr);
    this.VKI_keyboard.appendChild(tbody);

    if (this.VKI_isIE6) {
      this.VKI_iframe = document.createElement('iframe');
      this.VKI_iframe.style.position = "absolute";
      this.VKI_iframe.style.border = "0px none";
      this.VKI_iframe.style.filter = "mask()";
      this.VKI_iframe.style.zIndex = "999999";
      this.VKI_iframe.src = this.VKI_imageURI;
    }


    /* ****************************************************************
     * Build or rebuild the keyboard keys
     *
     */
    this.VKI_buildKeys = function() {
      this.VKI_shift = this.VKI_shiftlock = this.VKI_altgr = this.VKI_altgrlock = this.VKI_dead = false;
      this.VKI_deadkeysOn = (this.VKI_layout[this.VKI_kt].DDK) ? false : this.VKI_keyboard.getElementsByTagName('label')[0].getElementsByTagName('input')[0].checked;

      var container = this.VKI_keyboard.tBodies[0].getElementsByTagName('div')[0];
      while (container.firstChild) container.removeChild(container.firstChild);

      for (var x = 0, hasDeadKey = false, lyt; lyt = this.VKI_layout[this.VKI_kt][x++];) {
        var table = document.createElement('table');
            table.cellSpacing = table.border = "0";
        if (lyt.length <= this.VKI_keyCenter) table.className = "keyboardInputCenter";
          var tbody = document.createElement('tbody');
            var tr = document.createElement('tr');
            for (var y = 0, lkey; lkey = lyt[y++];) {
              var td = document.createElement('td');
                if (this.VKI_symbol[lkey[0]]) {
                  var span = document.createElement('span');
                      span.className = lkey[0];
                      span.appendChild(document.createTextNode(this.VKI_symbol[lkey[0]]));
                    td.appendChild(span);
                } else td.appendChild(document.createTextNode(lkey[0] || "\xa0"));

                var className = [];
                if (this.VKI_deadkeysOn)
                  for (key in this.VKI_deadkey)
                    if (key === lkey[0]) { className.push("alive"); break; }
                if (lyt.length > this.VKI_keyCenter && y == lyt.length) className.push("last");
                if (lkey[0] == " ") className.push("space");
                  td.className = className.join(" ");

                  td.VKI_clickless = 0;
                  if (!td.click) {
                    td.click = function() {
                      var evt = this.ownerDocument.createEvent('MouseEvents');
                      evt.initMouseEvent('click', true, true, this.ownerDocument.defaultView, 1, 0, 0, 0, 0, false, false, false, false, 0, null);
                      this.dispatchEvent(evt);
                    };
                  }
                  td.onmouseover = function() {
                    if (self.VKI_clickless) {
                      var _self = this;
                      clearTimeout(this.VKI_clickless);
                      this.VKI_clickless = setTimeout(function() { _self.click(); }, self.VKI_clickless);
                    }
                    if ((this.firstChild.nodeValue || this.firstChild.className) != "\xa0") this.className += " hover";
                  };
                  td.onmouseout = function() {
                    if (self.VKI_clickless) clearTimeout(this.VKI_clickless);
                    this.className = this.className.replace(/ ?(hover|pressed)/g, "");
                  };
                  td.onmousedown = function() {
                    if (self.VKI_clickless) clearTimeout(this.VKI_clickless);
                    if ((this.firstChild.nodeValue || this.firstChild.className) != "\xa0") this.className += " pressed";
                  };
                  td.onmouseup = function() {
                    if (self.VKI_clickless) clearTimeout(this.VKI_clickless);
                    this.className = this.className.replace(/ ?pressed/g, "");
                  };
                  td.ondblclick = function() { return false; };

                switch (lkey[1]) {
                  case "Caps": case "Shift":
                  case "Alt": case "AltGr": case "AltLk":
                    td.onclick = (function(type) { return function() { self.VKI_modify(type); return false; }; })(lkey[1]);
                    break;
                  case "Tab":
                    td.onclick = function() { self.VKI_insert("\t"); return false; };
                    break;
                  case "Bksp":
                    td.onclick = function() {
                      self.VKI_target.focus();
                      if (self.VKI_target.setSelectionRange && !self.VKI_target.readOnly) {
                        var rng = [self.VKI_target.selectionStart, self.VKI_target.selectionEnd];
                        if (rng[0] < rng[1]) rng[0]++;
                        self.VKI_target.value = self.VKI_target.value.substr(0, rng[0] - 1) + self.VKI_target.value.substr(rng[1]);
                        self.VKI_target.setSelectionRange(rng[0] - 1, rng[0] - 1);
                      } else if (self.VKI_target.createTextRange && !self.VKI_target.readOnly) {
                        try {
                          self.VKI_target.range.select();
                        } catch(e) { self.VKI_target.range = document.selection.createRange(); }
                        if (!self.VKI_target.range.text.length) self.VKI_target.range.moveStart('character', -1);
                        self.VKI_target.range.text = "";
                      } else self.VKI_target.value = self.VKI_target.value.substr(0, self.VKI_target.value.length - 1);
                      if (self.VKI_shift) self.VKI_modify("Shift");
                      if (self.VKI_altgr) self.VKI_modify("AltGr");
                      self.VKI_target.focus();
                      return true;
                    };
                    break;
                  case "Enter":
                    td.onclick = function() {
                      if (self.VKI_target.nodeName != "TEXTAREA") {
                        self.VKI_close();
                        this.className = this.className.replace(/ ?(hover|pressed)/g, "");
                      } else self.VKI_insert("\n");
                      return true;
                    };
                    break;
                  default:
                    td.onclick = function() {
                      var character = this.firstChild.nodeValue || this.firstChild.className;
                      if (self.VKI_deadkeysOn && self.VKI_dead) {
                        if (self.VKI_dead != character) {
                          for (key in self.VKI_deadkey) {
                            if (key == self.VKI_dead) {
                              if (character != " ") {
                                for (var z = 0, rezzed = false, dk; dk = self.VKI_deadkey[key][z++];) {
                                  if (dk[0] == character) {
                                    self.VKI_insert(dk[1]);
                                    rezzed = true;
                                    break;
                                  }
                                }
                              } else {
                                self.VKI_insert(self.VKI_dead);
                                rezzed = true;
                              } break;
                            }
                          }
                        } else rezzed = true;
                      } self.VKI_dead = false;

                      if (!rezzed && character != "\xa0") {
                        if (self.VKI_deadkeysOn) {
                          for (key in self.VKI_deadkey) {
                            if (key == character) {
                              self.VKI_dead = key;
                              this.className += " dead";
                              if (self.VKI_shift) self.VKI_modify("Shift");
                              if (self.VKI_altgr) self.VKI_modify("AltGr");
                              break;
                            }
                          }
                          if (!self.VKI_dead) self.VKI_insert(character);
                        } else self.VKI_insert(character);
                      }

                      self.VKI_modify("");
                      if (self.VKI_isOpera) {
                        this.style.width = "50px";
                        var foo = this.offsetWidth;
                        this.style.width = "";
                      }
                      return false;
                    };

                }
                tr.appendChild(td);
              tbody.appendChild(tr);
            table.appendChild(tbody);

            for (var z = 0; z < 4; z++)
              if (this.VKI_deadkey[lkey[z] = lkey[z] || "\xa0"]) hasDeadKey = true;
        }
        container.appendChild(table);
      }
      this.VKI_deadkeysElem.style.display = (!this.VKI_layout[this.VKI_kt].DDK && hasDeadKey) ? "inline" : "none";
    };

    this.VKI_buildKeys();
    VKI_disableSelection(this.VKI_keyboard);


    /* ****************************************************************
     * Controls modifier keys
     *
     */
    this.VKI_modify = function(type) {
      switch (type) {
        case "Alt":
        case "AltGr": this.VKI_altgr = !this.VKI_altgr; break;
        case "AltLk": this.VKI_altgrlock = !this.VKI_altgrlock; break;
        case "Caps": this.VKI_shiftlock = !this.VKI_shiftlock; break;
        case "Shift": this.VKI_shift = !this.VKI_shift; break;
      } var vchar = 0;
      if (!this.VKI_shift != !this.VKI_shiftlock) vchar += 1;
      if (!this.VKI_altgr != !this.VKI_altgrlock) vchar += 2;

      var tables = this.VKI_keyboard.getElementsByTagName('table');
      for (var x = 0; x < tables.length; x++) {
        var tds = tables[x].getElementsByTagName('td');
        for (var y = 0; y < tds.length; y++) {
          var className = [], lkey = this.VKI_layout[this.VKI_kt][x][y];

          if (tds[y].className.indexOf('hover') > -1) className.push("hover");

          switch (lkey[1]) {
            case "Alt":
            case "AltGr":
              if (this.VKI_altgr) className.push("dead");
              break;
            case "AltLk":
              if (this.VKI_altgrlock) className.push("dead");
              break;
            case "Shift":
              if (this.VKI_shift) className.push("dead");
              break;
            case "Caps":
              if (this.VKI_shiftlock) className.push("dead");
              break;
            case "Tab": case "Enter": case "Bksp": break;
            default:
              if (type) {
                tds[y].removeChild(tds[y].firstChild);
                if (this.VKI_symbol[lkey[vchar]]) {
                  var span = document.createElement('span');
                      span.className = lkey[vchar];
                      span.appendChild(document.createTextNode(this.VKI_symbol[lkey[vchar]]));
                    tds[y].appendChild(span);
                } else tds[y].appendChild(document.createTextNode(lkey[vchar]));
              }
              if (this.VKI_deadkeysOn) {
                var character = tds[y].firstChild.nodeValue || tds[y].firstChild.className;
                if (this.VKI_dead) {
                  if (character == this.VKI_dead) className.push("dead");
                  for (var z = 0; z < this.VKI_deadkey[this.VKI_dead].length; z++) {
                    if (character == this.VKI_deadkey[this.VKI_dead][z][0]) {
                      className.push("target");
                      break;
                    }
                  }
                }
                for (key in this.VKI_deadkey)
                  if (key === character) { className.push("alive"); break; }
              }
          }

          if (y == tds.length - 1 && tds.length > this.VKI_keyCenter) className.push("last");
          if (lkey[0] == " ") className.push("space");
          tds[y].className = className.join(" ");
        }
      }
    };


    /* ****************************************************************
     * Insert text at the cursor
     *
     */
    this.VKI_insert = function(text) {
      this.VKI_target.focus();
      if (this.VKI_target.maxLength) this.VKI_target.maxlength = this.VKI_target.maxLength;
      if (typeof this.VKI_target.maxlength == "undefined" ||
          this.VKI_target.maxlength < 0 ||
          this.VKI_target.value.length < this.VKI_target.maxlength) {
        if (this.VKI_target.setSelectionRange && !this.VKI_target.readOnly) {
          var rng = [this.VKI_target.selectionStart, this.VKI_target.selectionEnd];
          this.VKI_target.value = this.VKI_target.value.substr(0, rng[0]) + text + this.VKI_target.value.substr(rng[1]);
          if (text == "\n" && window.opera) rng[0]++;
          this.VKI_target.setSelectionRange(rng[0] + text.length, rng[0] + text.length);
        } else if (this.VKI_target.createTextRange && !this.VKI_target.readOnly) {
          try {
            this.VKI_target.range.select();
          } catch(e) { this.VKI_target.range = document.selection.createRange(); }
          this.VKI_target.range.text = text;
          this.VKI_target.range.collapse(true);
          this.VKI_target.range.select();
        } else this.VKI_target.value += text;
        if (this.VKI_shift) this.VKI_modify("Shift");
        if (this.VKI_altgr) this.VKI_modify("AltGr");
        this.VKI_target.focus();
      } else if (this.VKI_target.createTextRange && this.VKI_target.range)
        this.VKI_target.range.select();
    };


    /* ****************************************************************
     * Show the keyboard interface
     *
     */
    this.VKI_show = function(elem) {
      if (!this.VKI_target) {
        this.VKI_target = elem;
        if (this.VKI_langAdapt && this.VKI_target.lang) {
          var chg = false, sub = [];
          for (ktype in this.VKI_layout) {
            if (typeof this.VKI_layout[ktype] == "object") {
              for (var x = 0; x < this.VKI_layout[ktype].lang.length; x++) {
                if (this.VKI_layout[ktype].lang[x].toLowerCase() == this.VKI_target.lang.toLowerCase()) {
                  chg = kblist.value = this.VKI_kt = ktype;
                  break;
                } else if (this.VKI_layout[ktype].lang[x].toLowerCase().indexOf(this.VKI_target.lang.toLowerCase()) == 0)
                  sub.push([this.VKI_layout[ktype].lang[x], ktype]);
              }
            } if (chg) break;
          } if (sub.length) {
            sub.sort(function (a, b) { return a[0].length - b[0].length; });
            chg = kblist.value = this.VKI_kt = sub[0][1];
          } if (chg) this.VKI_buildKeys();
        }
        if (this.VKI_isIE) {
          if (!this.VKI_target.range) {
            this.VKI_target.range = this.VKI_target.createTextRange();
            this.VKI_target.range.moveStart('character', this.VKI_target.value.length);
          } this.VKI_target.range.select();
        }
        try { this.VKI_keyboard.parentNode.removeChild(this.VKI_keyboard); } catch (e) {}
        if (this.VKI_clearPasswords && this.VKI_target.type == "password") this.VKI_target.value = "";

        var elem = this.VKI_target;
        this.VKI_target.keyboardPosition = "absolute";
        do {
          if (VKI_getStyle(elem, "position") == "fixed") {
            this.VKI_target.keyboardPosition = "fixed";
            break;
          }
        } while (elem = elem.offsetParent);

        if (this.VKI_isIE6) document.body.appendChild(this.VKI_iframe);
        document.body.appendChild(this.VKI_keyboard);
        this.VKI_keyboard.style.position = this.VKI_target.keyboardPosition;
        if (this.VKI_isOpera) {
          if (this.VKI_sizeAdj) kbsize.value = this.VKI_size;
          kblist.value = this.VKI_kt;
        }

        this.VKI_position(true);
        if (self.VKI_isMoz || self.VKI_isWebKit) this.VKI_position(true);
        this.VKI_target.focus();
      } else this.VKI_close();
    };


    /* ****************************************************************
     * Position the keyboard
     *
     */
    this.VKI_position = function(force) {
      if (self.VKI_target) {
        var kPos = VKI_findPos(self.VKI_keyboard), wDim = VKI_innerDimensions(), sDis = VKI_scrollDist();
        var place = false, fudge = self.VKI_target.offsetHeight + 3;
        if (force !== true) {
          if (kPos[1] + self.VKI_keyboard.offsetHeight - sDis[1] - wDim[1] > 0) {
            place = true;
            fudge = -self.VKI_keyboard.offsetHeight - 3;
          } else if (kPos[1] - sDis[1] < 0) place = true;
        }
        if (place || force === true) {
          var iPos = VKI_findPos(self.VKI_target);
          self.VKI_keyboard.style.top = iPos[1] - ((self.VKI_target.keyboardPosition == "fixed" && !self.VKI_isIE && !self.VKI_isMoz) ? sDis[1] : 0) + fudge + "px";
          self.VKI_keyboard.style.left = Math.max(10, Math.min(wDim[0] - self.VKI_keyboard.offsetWidth - 25, iPos[0])) + "px";
          if (self.VKI_isIE6) {
            self.VKI_iframe.style.width = self.VKI_keyboard.offsetWidth + "px";
            self.VKI_iframe.style.height = self.VKI_keyboard.offsetHeight + "px";
            self.VKI_iframe.style.top = self.VKI_keyboard.style.top;
            self.VKI_iframe.style.left = self.VKI_keyboard.style.left;
          }
        }
        if (force === true) self.VKI_position();
      }
    };


    if (window.addEventListener) {
      window.addEventListener('resize', this.VKI_position, false);
      window.addEventListener('scroll', this.VKI_position, false);
    } else if (window.attachEvent) {
      window.attachEvent('onresize', this.VKI_position);
      window.attachEvent('onscroll', this.VKI_position);
    }
    if (this.VKI_sizeAdj) kbsize.change();


    /* ****************************************************************
     * Close the keyboard interface
     *
     */
    this.VKI_close = VKI_close = function() {
      if (this.VKI_target) {
        try {
          this.VKI_keyboard.parentNode.removeChild(this.VKI_keyboard);
          if (this.VKI_isIE6) this.VKI_iframe.parentNode.removeChild(this.VKI_iframe);
        } catch (e) {}
        if (this.VKI_kt != this.VKI_kts) {
          kblist.value = this.VKI_kt = this.VKI_kts;
          this.VKI_buildKeys();
        }
        this.VKI_target.focus();
        if (this.VKI_isIE) {
          setTimeout(function() { self.VKI_target = false; }, 0);
        } else this.VKI_target = false;
      }
    };
  }

  function VKI_findPos(obj) {
    var curleft = curtop = 0;
    do {
      curleft += obj.offsetLeft;
      curtop += obj.offsetTop;
    } while (obj = obj.offsetParent);
    return [curleft, curtop];
  }

  function VKI_innerDimensions() {
    if (self.innerHeight) {
      return [self.innerWidth, self.innerHeight];
    } else if (document.documentElement && document.documentElement.clientHeight) {
      return [document.documentElement.clientWidth, document.documentElement.clientHeight];
    } else if (document.body)
      return [document.body.clientWidth, document.body.clientHeight];
    return [0, 0];
  }

  function VKI_scrollDist() {
    var html = document.getElementsByTagName('html')[0];
    if (html.scrollTop && document.documentElement.scrollTop) {
      return [html.scrollLeft, html.scrollTop];
    } else if (html.scrollTop || document.documentElement.scrollTop) {
      return [html.scrollLeft + document.documentElement.scrollLeft, html.scrollTop + document.documentElement.scrollTop];
    } else if (document.body.scrollTop)
      return [document.body.scrollLeft, document.body.scrollTop];
    return [0, 0];
  }

  function VKI_getStyle(obj, styleProp) {
    if (obj.currentStyle) {
      var y = obj.currentStyle[styleProp];
    } else if (window.getComputedStyle)
      var y = window.getComputedStyle(obj, null)[styleProp];
    return y;
  }

  function VKI_disableSelection(elem) {
    elem.onselectstart = function() { return false; };
    elem.unselectable = "on";
    elem.style.MozUserSelect = "none";
    elem.style.cursor = "default";
    if (window.opera) elem.onmousedown = function() { return false; };
  }


  /* ***** Attach this script to the onload event ****************** */
  if (window.addEventListener) {
    window.addEventListener('load', VKI_buildKeyboardInputs, false);
  } else if (window.attachEvent)
    window.attachEvent('onload', VKI_buildKeyboardInputs);