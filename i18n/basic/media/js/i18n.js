/**
 * I18n
 *
 * @type object
 */
numbers.i18n.__custom = {

	/**
	 * Data
	 *
	 * @type object
	 */
	data: {},

	/**
	 * Missing translations
	 *
	 * @type object
	 */
	missing: {}, // todo send it to back end

	/**
	 * Get translation
	 *
	 * @param string i18n
	 * @param string text
	 * @param array options
	 * @return string
	 */
	get: function(i18n, text, options) {
		if (empty(i18n)) {
			// some texts we skip
			if (in_array(text, [' ', '&nbsp;', '-', '', '  '])) {
				return text;
			}
			// integers and floats
			if (is_numeric(text)) {
				return numbers.format.id(text);
			}
		}
		// if we have i18n
		if (!empty(i18n) && isset(this.data['ids']) && isset(this.data['ids'][i18n])) {
			return this.data['hashes'][this.data['ids'][i18n]];
		}
		// we translate if language is not system
		var format = array_key_get(numbers, 'flag.global.format');
		if (format.language_code && format.language_code != 'sys') {
			var hash = text;
			if (hash.length > 40) {
				hash = sha1(hash);
			}
			if (this.data['hashes'].hasOwnProperty(hash)) {
				text = this.data['hashes'][hash];
			} else {
				// put data into missing
				if (format.language_code != 'eng') {
					// todo: handle missing
					if (!isset(this.missing[text])) {
						this.missing[text] = 1;
					} else {
						this.missing[text]+= 1;
					}
				}
			}
		} else if (format.language_code == 'sys') {
			text+= ' i';
		}
		return text;
	}
};