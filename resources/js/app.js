const breeds = {

    dog: [
        'Aspin (Mixed Breed)',
        'Labrador Retriever',
        'Golden Retriever',
        'German Shepherd',
        'Beagle',
        'Bulldog',
        'Poodle',
        'Shih Tzu',
        'Chihuahua',
        'Dachshund',
        'Siberian Husky',
        'Rottweiler',
        'Boxer',
        'Doberman',
        'Maltese',
        'Pomeranian',
        'Chow Chow',
        'Dalmatian',
        'Border Collie',
        'Cocker Spaniel',
        'Other',
    ],

    cat: [
        'Puspin (Mixed Breed)',
        'Persian',
        'Siamese',
        'Maine Coon',
        'Ragdoll',
        'Bengal',
        'British Shorthair',
        'Abyssinian',
        'Scottish Fold',
        'Sphynx',
        'Russian Blue',
        'Birman',
        'Burmese',
        'Tabby',
        'Domestic Shorthair',
        'Domestic Longhair',
        'Other',
    ],

    small_pet: [
        'Guinea Pig',
        'Hamster',
        'Rabbit',
        'Gerbil',
        'Mouse',
        'Rat',
        'Chinchilla',
        'Ferret',
        'Hedgehog',
        'Parakeet / Budgie',
        'Cockatiel',
        'Turtle',
        'Gecko',
        'Other',
    ],
};

const currentBreed = "{{ old('breed', $pet->breed ?? '') }}";

function updateBreeds()
{
    const species = document.getElementById('species').value;
    const breedSel = document.getElementById('breed');

    breedSel.innerHTML = '';

    if (!species || !breeds[species]) {

        breedSel.innerHTML =
            '<option value="">Select species first...</option>';

        return;
    }

    const ph = document.createElement('option');

    ph.value = '';
    ph.textContent = 'Select breed...';
    ph.disabled = true;
    ph.selected = true;

    breedSel.appendChild(ph);

    breeds[species].forEach(function (breed) {

        const opt = document.createElement('option');

        opt.value = breed;
        opt.textContent = breed;

        if (breed === currentBreed) {
            opt.selected = true;
        }

        breedSel.appendChild(opt);
    });
}

document.addEventListener('DOMContentLoaded', function () {

    if (document.getElementById('species').value) {
        updateBreeds();
    }

});

document.addEventListener('DOMContentLoaded', function () {

    const speciesSelect = document.getElementById('species');

    if (speciesSelect.value) {
        updateBreeds();
    }

    speciesSelect.addEventListener('change', updateBreeds);

});