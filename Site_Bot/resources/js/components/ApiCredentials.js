import React, {useState} from 'react'
import axios from 'axios'

export default function ApiCredentials() {

	const [formFields, addToForm] = useState({
		apiKey: '',
		secret: '',
	})

    function addFieldToForm(event){
		const target = event.target
		var fieldName = event.target.getAttribute('name')
		var newFormFields = { ...formFields }
		newFormFields[fieldName] = target.value

		addToForm(newFormFields)
	}

	function saveApiCredentials(event){
		event.preventDefault()

		const api = {
            //_token: csrf,
            api_key: formFields.apiKey,
            api_secret: formFields.secret,
            user_id: currentUserId,
		}

		axios.post(baseUrl + '/save-api-credentials', api)
		.then((response)=>{
			console.log(response)
		})
		.catch(response => {
			console.log('catch', response)
		})
	}

    return (
        <div className="row justify-content-center">
            <form className="w-50">
                <label htmlFor="apiKey" className="form-label">API key</label>
                <input type="text" className="form-control mb-3" name="apiKey" onChange={addFieldToForm} />
                <label htmlFor="secret" className="form-label">Secret key</label>
                <input type="text" className="form-control mb-3" name="secret" onChange={addFieldToForm} />
                <button type="submit" className="btn btn-primary w-100" onClick={saveApiCredentials}>Sign In</button>
            </form>
        </div>
    )
}
