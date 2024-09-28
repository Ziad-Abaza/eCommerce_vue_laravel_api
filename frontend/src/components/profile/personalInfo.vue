<template>
  <div class="user-container">
    <div class="user-info" v-if="!isEditing && data != null">
      <h2 class="section-title">User Information</h2>
      <div class="info-item"><span>Name:</span> {{ data.name }}</div>
      <div class="info-item"><span>Email:</span> {{ data.email }}</div>
      <div class="info-item"><span>Phone Number:</span> {{ data.phone }}</div>
      <div class="info-item">
        <span>Active:</span>
        <strong :class="{ 'active': data.isActive, 'inactive': !data.isActive }">
          {{ data.isActive ? 'Yes' : 'No' }}
        </strong>
      </div>
      <button @click="changeData" class="edit-button">Edit</button>
    </div>

    <div class="address-info" v-if="!isEditing && data != null">
      <h2 class="section-title">Address</h2>
      <div class="info-item"><span>Address:</span> {{ data.address }}</div>
    </div>

    <div class="user-edit" v-if="isEditing && data != null">
      <h2 class="section-title">Edit User Information</h2>
      <form @submit.prevent="submitEdit">
        <div class="form-item">
          <label>Name:</label>
          <input type="text" v-model="editData.name" required />
        </div>
        <div class="form-item">
          <label>Email:</label>
          <input type="email" v-model="editData.email" required />
        </div>
        <div class="form-item">
          <label>Phone:</label>
          <input type="tel" v-model="editData.phone" required />
        </div>
        <div class="form-item">
          <label>Address:</label>
          <input type="text" v-model="editData.address" required />
        </div>
        <div class="button-group">
          <button type="submit" class="save-button">Save</button>
          <button @click="cancelEdit" type="button" class="cancel-button">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, defineProps, computed } from 'vue';
import { useUserStore } from '@/stores/userStore';

const userStore = useUserStore();
const props = defineProps({
  data: Object
});

const isEditing = ref(false);

const userData = computed(() => props.data);

const editData = ref({ ...userData.value });

const changeData = () => {
  isEditing.value = true;
  editData.value = { ...userData.value }; 
};

const submitEdit = () => {
  userStore.updateUser(editData.value);
  console.log('Edited data submitted:', editData.value);
  isEditing.value = false; 
};

const cancelEdit = () => {
  isEditing.value = false; 
};
</script>

<style scoped>
.user-container {
  width: 100%;
  margin: 20px auto;
  padding: 20px;
  border-radius: 10px;
  background-color: #f7f9fc;
}

.user-info,
.address-info,
.user-edit {
  margin-bottom: 20px;
  padding: 20px;
  border: 1px solid #e0e0e0;
  border-radius: 10px;
  background-color: #ffffff;
  transition: transform 0.3s, box-shadow 0.3s;
}

.user-info:hover,
.address-info:hover,
.user-edit:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.section-title {
  font-size: 1.6rem;
  margin-bottom: 12px;
  font-weight: bold;
  color: #333;
}

.info-item {
  margin-bottom: 10px;
}

.info-item span {
  font-weight: bold;
  color: #555;
}

.active {
  color: #4CAF50; /* Green for active */
  font-weight: bold;
}

.inactive {
  color: #f44336; /* Red for inactive */
  font-weight: bold;
}

.edit-button,
.save-button,
.cancel-button {
  margin-top: 10px;
  padding: 12px 18px;
  font-size: 1em;
  color: #fff;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  transition: background-color 0.3s, transform 0.2s;
}

.edit-button {
  background-color: #2196F3; /* Blue for edit */
}

.save-button {
  background-color: #4CAF50; /* Green for save */
}

.cancel-button {
  background-color: #f44336; /* Red for cancel */
}

.edit-button:hover,
.save-button:hover,
.cancel-button:hover {
  opacity: 0.9; /* Slight opacity on hover */
  transform: translateY(-2px); /* Button lift effect */
}

.form-item {
  margin-bottom: 20px;
}

.form-item label {
  display: block;
  margin-bottom: 8px;
  color: #555;
}

.form-item input {
  width: 100%;
  padding: 12px;
  border: 1px solid #ccc;
  border-radius: 5px;
  transition: border-color 0.3s, box-shadow 0.3s;
}

.form-item input:focus {
  border-color: #4CAF50; /* Green border on focus */
  box-shadow: 0 0 5px rgba(76, 175, 80, 0.5);
}

.button-group {
  display: flex;
  gap: 10px; /* Space between buttons */
}
</style>
