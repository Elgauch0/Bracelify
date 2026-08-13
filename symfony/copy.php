{% extends 'base.html.twig' %}

{% block body %}
	{% set base_btn_class = "px-4 py-2 rounded-lg border shrink-0 transition" %}
	{% set active_class = "bg-tertiary text-white" %}
	{% set inactive_class = "hover:bg-tertiary hover:text-white" %}

	<div class="m-12 flex flex-col items-center gap-6">
		<h1 class="text-3xl font-bold">Nos Produits :</h1>

		{# NAV DES CATÉGORIES #}
		<nav class="w-full overflow-hidden">
			<div
				class="flex gap-4 overflow-x-auto justify-center overflow-y-hidden pb-2 scrollbar-none max-w-full">

				{# Bouton : Toutes les catégories (conserve le filtre collection s'il existe) #}
				<a href="{{ path('app_public_products', { collection: currentCollection|default(null) }|filter(v => v is not null)) }}" class="{{ base_btn_class }} {{ not currentCategory ? active_class : inactive_class }}">
					Toutes les catégories
				</a>

				{% for category in categories %}
					{% set is_active = (currentCategory == category.id) %}
					<a href="{{ path('app_public_products', { category: category.id, collection: currentCollection|default(null) }|filter(v => v is not null)) }}" class="{{ base_btn_class }} {{ is_active ? active_class : inactive_class }}">
						{{ category.label }}
					</a>
				{% endfor %}

			</div>
		</nav>

		{# NAV DES COLLECTIONS #}
		<nav class="w-full overflow-hidden">
			<div
				class="flex gap-4 overflow-x-auto justify-center overflow-y-hidden pb-2 scrollbar-none max-w-full">

				{# Bouton : Toutes les collections (conserve le filtre catégorie s'il existe) #}
				<a href="{{ path('app_public_products', { category: currentCategory|default(null) }|filter(v => v is not null)) }}" class="{{ base_btn_class }} {{ not currentCollection ? active_class : inactive_class }}">
					Toutes les collections
				</a>

				{% for collection in collections %}
					{% set is_active = (currentCollection == collection.id) %}
					<a href="{{ path('app_public_products', { category: currentCategory|default(null), collection: collection.id }|filter(v => v is not null)) }}" class="{{ base_btn_class }} {{ is_active ? active_class : inactive_class }}">
						{{ collection.label }}
					</a>
				{% endfor %}

			</div>
		</nav>
	</div>

	{# GRID DES PRODUITS #}
	<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
		{% for product in products %}
			<twig:product_card :product="product"/>
		{% else %}
			<p class="text-center text-gray-500 col-span-full">
				Aucun produit disponible pour cette sélection.
			</p>
		{% endfor %}
	</div>
{% endblock %}
