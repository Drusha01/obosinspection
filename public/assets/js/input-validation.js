function specialCharactersAndNumbersValidation (element) {
    if (document.getElementById(element)) {
        let elementInput = document.getElementById(element);

        elementInput.addEventListener('input', () => {
            const elementValue = elementInput.value.trim();
            if (elementValue === '') {
              elementInput.setCustomValidity('Please enter a value. Avoid inputting whitespaces');
            } else if (/^[0-9]+$/.test(elementValue)) {
              elementInput.setCustomValidity('Numbers are not allowed.');
            } else if (/^[~`!@#$%^&*()_=+[\]{}|;:'",<>/?]+$/.test(elementValue)) {
              elementInput.setCustomValidity('Special Characters are not allowed.');
            } else if (!/^[a-zA-Z\s-.ñÑ]+$/.test(elementValue)) {
              elementInput.setCustomValidity('Numbers and Special Characters are not allowed.');
            } else {
              elementInput.setCustomValidity('');
            }
          });
    }
}

specialCharactersAndNumbersValidation('inspector-firstname');
specialCharactersAndNumbersValidation('inspector-lastname');
specialCharactersAndNumbersValidation('owner-firstname');
specialCharactersAndNumbersValidation('owner-lastname');



