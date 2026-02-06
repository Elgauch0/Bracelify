{% extends 'base.html.twig' %}

{% block title %}Register
{% endblock %}


{% block body %}
	<h1 class="text-2xl text-tertiary font-bold m-4 text-center">
		Créer un compte
	</h1>

	<div class="flex justify-center items-center">
		<div class="w-full max-w-md bg-white p-6 rounded-lg shadow">
			{{ form(registrationForm) }}
			</div>
		</div>
	{% endblock %}
