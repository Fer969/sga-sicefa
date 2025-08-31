<form action="#" method="POST">
    @csrf
    <div class="accordion" id="apprenticeFormAccordion">
        <!-- 1. Datos del Aprendiz -->
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingOne">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                    <strong>1. Datos del Aprendiz</strong>
                </button>
            </h2>
            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#apprenticeFormAccordion">
                <div class="accordion-body">
                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label for="full_name">Nombres y Apellidos</label>
                            <input type="text" class="form-control" id="full_name" name="full_name" value="Juan Carlos Pérez López" required>
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label for="document_type">Tipo de Documento</label>
                            <select class="form-control" id="document_type" name="document_type" required>
                                <option value="CC">Cédula de Ciudadanía (CC)</option>
                                <option value="TI">Tarjeta de Identidad (TI)</option>
                                <option value="CE">Cédula de Extranjería (CE)</option>
                                <option value="Otra">Otra</option>
                            </select>
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label for="document_number">Número de Documento</label>
                            <input type="text" class="form-control" id="document_number" name="document_number" value="1234567890" required>
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label for="document_issue_place">Lugar de Expedición</label>
                            <input type="text" class="form-control" id="document_issue_place" name="document_issue_place" value="Bogotá" required>
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label for="gender">Género</label>
                            <select class="form-control" id="gender" name="gender" required>
                                <option value="Femenino">Femenino</option>
                                <option value="Masculino">Masculino</option>
                                <option value="No Binario">No Binario</option>
                            </select>
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label for="age">Edad</label>
                            <input type="number" class="form-control" id="age" name="age" value="20" required>
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label for="sisben_group">Grupo SISBEN</label>
                            <select class="form-control" id="sisben_group" name="sisben_group" required>
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="C">C</option>
                                <option value="D">D</option>
                            </select>
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label for="sisben_level">Nivel SISBEN</label>
                            <input type="number" class="form-control" id="sisben_level" name="sisben_level" value="2" required>
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label for="residence_department">Departamento de Residencia</label>
                            <input type="text" class="form-control" id="residence_department" name="residence_department" value="Cundinamarca" required>
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label for="residence_municipality">Municipio de Residencia</label>
                            <input type="text" class="form-control" id="residence_municipality" name="residence_municipality" value="Soacha" required>
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label for="address">Dirección</label>
                            <input type="text" class="form-control" id="address" name="address" value="Calle 123 #45-67" required>
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label for="contact_number">Número de Contacto</label>
                            <input type="text" class="form-control" id="contact_number" name="contact_number" value="3001234567" required>
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label for="email">Correo Electrónico</label>
                            <input type="email" class="form-control" id="email" name="email" value="aprendiz@email.com" required>
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label for="emergency_contact_name">Nombre de Persona de Contacto</label>
                            <input type="text" class="form-control" id="emergency_contact_name" name="emergency_contact_name" value="María López" required>
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label for="emergency_contact_number">Teléfono de Contacto</label>
                            <input type="text" class="form-control" id="emergency_contact_number" name="emergency_contact_number" value="3109876543" required>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- 2. Datos de Formación -->
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingTwo">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                    <strong>2. Datos de Formación</strong>
                </button>
            </h2>
            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#apprenticeFormAccordion">
                <div class="accordion-body">
                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label for="training_program">Programa de Formación</label>
                            <input type="text" class="form-control" id="training_program" name="training_program" value="Técnico en Sistemas" required>
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label for="training_file_number">Número de Ficha</label>
                            <input type="text" class="form-control" id="training_file_number" name="training_file_number" value="2567890" required>
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label for="training_modality">Modalidad de Formación</label>
                            <select class="form-control" id="training_modality" name="training_modality" required>
                                <option value="Presencial">Presencial</option>
                                <option value="Virtual">Virtual</option>
                                <option value="A Distancia">A Distancia</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- 3. Datos del Representante Legal o Tutor -->
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingThree">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                    <strong>3. Datos del Representante Legal o Tutor (si es menor de edad)</strong>
                </button>
            </h2>
            <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#apprenticeFormAccordion">
                <div class="accordion-body">
                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label for="legal_representative_name">Nombres y Apellidos</label>
                            <input type="text" class="form-control" id="legal_representative_name" name="legal_representative_name">
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label for="legal_representative_document">Tipo y Número de Documento</label>
                            <input type="text" class="form-control" id="legal_representative_document" name="legal_representative_document">
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label for="legal_representative_department">Departamento</label>
                            <input type="text" class="form-control" id="legal_representative_department" name="legal_representative_department">
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label for="legal_representative_municipality">Municipio</label>
                            <input type="text" class="form-control" id="legal_representative_municipality" name="legal_representative_municipality">
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label for="legal_representative_address">Dirección</label>
                            <input type="text" class="form-control" id="legal_representative_address" name="legal_representative_address">
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label for="legal_representative_contact_number">Número de Contacto</label>
                            <input type="text" class="form-control" id="legal_representative_contact_number" name="legal_representative_contact_number">
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label for="legal_representative_email">Correo Electrónico</label>
                            <input type="email" class="form-control" id="legal_representative_email" name="legal_representative_email">
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label for="legal_representative_relationship">Parentesco</label>
                            <input type="text" class="form-control" id="legal_representative_relationship" name="legal_representative_relationship">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- 4. Vivienda -->
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingFour">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                    <strong>4. Vivienda</strong>
                </button>
            </h2>
            <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#apprenticeFormAccordion">
                <div class="accordion-body">
                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label for="housing_location">Ubicación</label>
                            <select class="form-control" id="housing_location" name="housing_location" required>
                                <option value="Rural">Rural</option>
                                <option value="Urbana">Urbana</option>
                            </select>
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label for="housing_stratum">Estrato</label>
                            <input type="number" class="form-control" id="housing_stratum" name="housing_stratum" value="2" required>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- 5. Servicio Médico del Aprendiz -->
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingFive">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                    <strong>5. Servicio Médico del Aprendiz</strong>
                </button>
            </h2>
            <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#apprenticeFormAccordion">
                <div class="accordion-body">
                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label for="health_regime">Tipo de Régimen</label>
                            <select class="form-control" id="health_regime" name="health_regime" required>
                                <option value="Contributivo">Contributivo</option>
                                <option value="Subsidiado">Subsidiado</option>
                            </select>
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label for="health_provider">EPS</label>
                            <input type="text" class="form-control" id="health_provider" name="health_provider" value="Salud Total" required>
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label for="health_link">Tipo de Vinculación</label>
                            <select class="form-control" id="health_link" name="health_link" required>
                                <option value="Cotizante">Cotizante</option>
                                <option value="Beneficiario">Beneficiario</option>
                                <option value="Cabeza de Familia">Cabeza de Familia</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- 6. Información Socioeconómica -->
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingSix">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                    <strong>6. Información Socioeconómica (Marcar con "Sí" o "No")</strong>
                </button>
            </h2>
            <div id="collapseSix" class="accordion-collapse collapse" aria-labelledby="headingSix" data-bs-parent="#apprenticeFormAccordion">
                <div class="accordion-body">
                    <div class="row">
                        <!-- Ejemplo de pregunta socioeconómica -->
                        <div class="col-md-6 form-group mb-3">
                            <label>¿Es beneficiario del Programa Renta Joven?</label>
                            <div>
                                <input type="radio" id="renta_joven_yes" name="renta_joven" value="Sí" required>
                                <label for="renta_joven_yes">Sí</label>
                                <input type="radio" id="renta_joven_no" name="renta_joven" value="No">
                                <label for="renta_joven_no">No</label>
                            </div>
                        </div>
                        <!-- ...agrega aquí el resto de preguntas socioeconómicas como en el ejemplo original... -->
                    </div>
                </div>
            </div>
        </div>
        <!-- 7. Condiciones del Aprendiz -->
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingSeven">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
                    <strong>7. Condiciones del Aprendiz (Marcar con "Sí" o "No")</strong>
                </button>
            </h2>
            <div id="collapseSeven" class="accordion-collapse collapse" aria-labelledby="headingSeven" data-bs-parent="#apprenticeFormAccordion">
                <div class="accordion-body">
                    <div class="row">
                        <!-- Ejemplo de condición -->
                        <div class="col-md-6 form-group mb-3">
                            <label>¿Es víctima del conflicto armado?</label>
                            <div>
                                <input type="radio" id="conflict_victim_yes" name="conflict_victim" value="Sí" required>
                                <label for="conflict_victim_yes">Sí</label>
                                <input type="radio" id="conflict_victim_no" name="conflict_victim" value="No">
                                <label for="conflict_victim_no">No</label>
                            </div>
                        </div>
                        <!-- ...agrega aquí el resto de condiciones como en el ejemplo original... -->
                    </div>
                </div>
            </div>
        </div>
        <!-- 8. Declaración Juramentada -->
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingEight">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEight" aria-expanded="false" aria-controls="collapseEight">
                    <strong>8. Declaración Juramentada</strong>
                </button>
            </h2>
            <div id="collapseEight" class="accordion-collapse collapse" aria-labelledby="headingEight" data-bs-parent="#apprenticeFormAccordion">
                <div class="accordion-body">
                    <p>Si no cuenta con soportes al momento de la convocatoria, el aprendiz puede presentar esta declaración firmada. Si resulta beneficiado, el SENA podrá solicitar evidencias en cualquier momento.</p>
                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label for="sworn_statement_name">Nombre y Apellidos</label>
                            <input type="text" class="form-control" id="sworn_statement_name" name="sworn_statement_name" value="Juan Carlos Pérez López" required>
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label for="sworn_statement_document">Número de Documento de Identidad</label>
                            <input type="text" class="form-control" id="sworn_statement_document" name="sworn_statement_document" value="1234567890" required>
                        </div>
                        <div class="col-md-12 form-group mb-3">
                            <label for="sworn_statement_condition">Condición que Declara</label>
                            <textarea class="form-control" id="sworn_statement_condition" name="sworn_statement_condition" rows="3" placeholder="Ejemplo: Aprendiz embarazada, aprendiz campesino, aprendiz en situación de discapacidad, etc." required></textarea>
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label for="sworn_statement_signature">Firma del Aprendiz (Escriba su nombre como firma)</label>
                            <input type="text" class="form-control" id="sworn_statement_signature" name="sworn_statement_signature" value="Juan Carlos Pérez López" required>
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label for="sworn_statement_representative_signature">Firma del Representante Legal o Tutor (si aplica)</label>
                            <input type="text" class="form-control" id="sworn_statement_representative_signature" name="sworn_statement_representative_signature">
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label for="sworn_statement_date">Fecha</label>
                            <input type="date" class="form-control" id="sworn_statement_date" name="sworn_statement_date" value="2024-12-01" required>
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label for="sworn_statement_city">Ciudad</label>
                            <input type="text" class="form-control" id="sworn_statement_city" name="sworn_statement_city" value="Soacha" required>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="text-end mt-4">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-paper-plane"></i> Enviar Solicitud
        </button>
    </div>
</form>