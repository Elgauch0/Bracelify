{% extends "base.html.twig" %}

{% block title %}À propos | Bracelify
{% endblock %}

{% block body %}


	<div
		class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 space-y-32">

		<!-- HERO -->
		<section class="relative bg-white text-center rounded-2xl shadow-md px-6 py-20 sm:py-24 mb-16">
			<div class="max-w-4xl mx-auto">

				<h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-text-darker leading-tight">
					L’art de créer avec passion
				</h1>

				<p class="mt-6 text-gray-500 max-w-2xl mx-auto">
					Bracelify conçoit des bracelets artisanaux pensés pour durer,
																									                raconter une histoire et refléter une personnalité.
				</p>

				<!-- STATS -->
				<div class="mt-12 flex justify-center flex-wrap gap-10">
					<div class="border-l-2 border-primary pl-4 text-left">
						<p class="text-2xl font-bold">100%</p>
						<p class="text-xs text-gray-400 uppercase">Fait main</p>
					</div>

					<div class="border-l-2 border-primary pl-4 text-left">
						<p class="text-2xl font-bold">+500</p>
						<p class="text-xs text-gray-400 uppercase">Créations</p>
					</div>

					<div class="border-l-2 border-primary pl-4 text-left">
						<p class="text-2xl font-bold">3 ans</p>
						<p class="text-xs text-gray-400 uppercase">Expérience</p>
					</div>
				</div>

			</div>
		</section>

		<!-- SECTION 1 -->
		<section class="grid md:grid-cols-2 gap-10 items-center py-10">
			<img src="{{ asset('images/bracelifyhand.jpg') }}" alt="Bracelet fait main" class="w-full max-w-md mx-auto rounded-2xl shadow-md object-cover">

			<div class="space-y-4">
				<span class="text-xs uppercase tracking-widest text-primary font-semibold">Mission</span>
				<h2 class="text-2xl md:text-3xl font-bold text-text-darker">
					Valoriser l’artisanat
				</h2>
				<p class="text-gray-600 leading-relaxed">
					Chaque bracelet est conçu comme une pièce unique, loin de la production de masse.
																									                Nous privilégions la qualité, la durabilité et l’émotion.
				</p>
			</div>
		</section>

		<!-- SECTION 2 -->
		<section class="grid md:grid-cols-2 gap-10 items-center">

			<div class="md:order-2">
				<img src="{{ asset('images/bracelifyhand2.jpg') }}" alt="Fabrication bracelet" class="w-full max-w-md mx-auto rounded-2xl shadow-md object-cover">
			</div>

			<div class="space-y-4 md:order-1">
				<span class="text-xs uppercase tracking-widest text-primary font-semibold">Savoir-faire</span>
				<h2 class="text-2xl md:text-3xl font-bold text-text-darker">
					Le travail de la main
				</h2>
				<p class="text-gray-600 leading-relaxed">
					Tressage, assemblage, finition — chaque étape est réalisée avec précision.
																									                Nous sélectionnons des matériaux durables pour garantir qualité et confort.
				</p>
			</div>
		</section>

		<!-- SECTION 3 -->
		<section class="grid md:grid-cols-2 gap-10 items-center">
			<img src="{{ asset('images/mizharia.jpg') }}" alt="Bracelet style" class="w-full max-w-md mx-auto rounded-2xl shadow-md object-cover">

			<div class="space-y-4">
				<span class="text-xs uppercase tracking-widest text-primary font-semibold">Identité</span>
				<h2 class="text-2xl md:text-3xl font-bold text-text-darker">
					Un style unique
				</h2>
				<p class="text-gray-600 leading-relaxed">
					Chaque création s’adapte à votre style.
																									                Minimaliste ou audacieuse, elle devient une extension de votre personnalité.
				</p>
			</div>
		</section>

		<!-- ENGAGEMENT -->
		<section class="bg-gray-50 rounded-2xl px-6 py-14 text-center mt-16">
			<h2 class="text-2xl md:text-3xl font-bold text-text-darker mb-4">
				Créer mieux, pas plus
			</h2>

			<p class="text-gray-600 max-w-2xl mx-auto mb-10">
				Nous produisons en petites séries, utilisons des matériaux responsables
																				            et privilégions les circuits courts.
			</p>

			<div class="grid sm:grid-cols-3 gap-8">
				<div>
					<p class="text-2xl">♻️</p>
					<p class="text-sm mt-2 text-gray-600">Emballages recyclés</p>
				</div>

				<div>
					<p class="text-2xl">🤝</p>
					<p class="text-sm mt-2 text-gray-600">Fournisseurs locaux</p>
				</div>

				<div>
					<p class="text-2xl">⏳</p>
					<p class="text-sm mt-2 text-gray-600">Durabilité</p>
				</div>
			</div>
		</section>

		<!-- CTA -->
		<section class="bg-primary text-white rounded-2xl text-center py-14 px-6">
			<h2 class="text-2xl md:text-3xl font-bold mb-4">
				Trouvez votre bracelet
			</h2>

			<p class="text-white/80 mb-6">
				Découvrez nos créations artisanales uniques.
			</p>

			<a href="{{ path('app_public_products') }}" class="inline-block bg-white text-primary px-6 py-3 rounded-full font-semibold transition transform hover:scale-105 hover:shadow-md">
				Voir la boutique
			</a>
		</section>

	</div>

{% endblock %}

