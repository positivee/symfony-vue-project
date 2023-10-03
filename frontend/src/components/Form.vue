
<template>
    <form @submit.prevent="formSubmit" class="form">
        <label for="name">Imię</label>
        <input name="name" type="text" v-model="formData.name" />
        <div class="error" v-if="errors.name">{{ errors.name }}</div>
        <br />
        <label>Nazwisko</label>
        <input type="text" v-model="formData.surname" />
        <div class="error" v-if="errors.surname">{{ errors.surname }}</div>
        <br />
        <label>E-mail</label>
        <input type="text" v-model="formData.email" />
        <div class="error" v-if="errors.email">{{ errors.email }}</div>
        <br />
        <label>Wiadomość</label>
        <textarea v-model="formData.message"></textarea>
        <div class="error" v-if="errors.message">{{ errors.message }}</div>
        <br />
        <button type="submit">Send messege</button>
    </form>

    <div class="success" v-if="sucessMessage">{{ sucessMessage }}

        <table>
            <thead>
                <tr>
                    <th>Imię </th>
                    <th>Nazwisko</th>
                    <th>Email</th>
                    <th>Wiadomość</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ sucessufllyAddedData.name }}</td>
                    <td>{{ sucessufllyAddedData.surname }}</td>
                    <td>{{ sucessufllyAddedData.email }}</td>
                    <td>{{ sucessufllyAddedData.message }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>


<script setup lang="ts">
import { ref } from 'vue';
// const emit = defineEmits<{
//     (e: 'reload', val: boolean): void
// }>()
const emit = defineEmits(['created']);
const errors = ref({ name: '', surname: '', email: '', message: '' });
const sucessMessage = ref('');
const sucessufllyAddedData = ref({ name: '', surname: '', email: '', message: '' });
const formData = ref({ name: '', surname: '', email: '', message: '' });


async function formSubmit() {
    validateForm();
    const allEmpty = Object.values(errors.value).every(value => value === '');


    if (allEmpty) {
        try {
            const response = await fetch('http://localhost:8000/forms/create', {
                method: 'POST',
                body: JSON.stringify(formData.value),
                headers: {
                    'Content-Type': 'application/json; charset=UTF-8',
                }
            });
            const data = await response.json();

            if (response.ok) {
                sucessufllyAddedData.value = formData.value;
                formData.value = { name: '', surname: '', email: '', message: '' };
                sucessMessage.value = data;
                emit('created');
            }

            if (response.status === 400) {
                errors.value = data;
            }


        } catch (err: any) {
            console.log(err);

        }

    }


}

function validateForm() {
    errors.value = { name: '', surname: '', email: '', message: '' };
    sucessMessage.value = '';
    if (!formData.value.name.trim()) {
        errors.value.name = "This value can't be blank";
    }

    if (!formData.value.surname.trim()) {
        errors.value.surname = "This value can't be blank";
    }

    if (!formData.value.message.trim()) {
        errors.value.message = "This value can't be blank";
    }

    if (!formData.value.email.trim()) {
        errors.value.email = "This value can't be blank";
    } else if (!/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/.test(formData.value.email)) {
        errors.value.email = "Invalid e-mail";
    }
}



</script>

<style>
.form {
    margin: 3rem 0;
}

.error {
    color: coral;
}

.success {
    color: green;
}
</style>