const messages = {
    required: 'This field is required.',
    email: 'Enter valid Email Address.',
    max: 'This field must not be greater than 255.',
    phoneNumber: 'Enter valid Phone Number',
}

const rules = {
    firstName: [
        { required: true, message: messages.required, trigger: 'blur' },
        { max: 255, message: messages.max, trigger: 'blur' },
    ],
    middleName: [
        { required: true, message: messages.required, trigger: 'blur' },
        { max: 255, message: messages.max, trigger: 'blur' },
    ],
    lastName: [
        { required: true, message: messages.required, trigger: 'blur' },
        { max: 255, message: messages.max, trigger: 'blur' },
    ],
    email: [
        { required: true, message: messages.required, trigger: 'blur' },
        { type: 'email', message: messages.email, trigger: 'blur' },
    ],
    phoneNumber: [
        { max: 255, message: messages.max, trigger: 'blur' },
        { pattern:/^\+\d{8,}$/, message: messages.phoneNumber, trigger: 'blur' },
    ],
    gender: [
        { required: true, message: messages.required, trigger: 'blur' },
    ],
    birthdate: [
        { required: true, message: messages.required, trigger: 'blur' },
    ],
}

export default rules;
