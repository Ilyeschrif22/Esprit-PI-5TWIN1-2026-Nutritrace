# Cahier des charges V2 — Module Traçabilité & Cartographie

## 1. Vision du module

Le module Traçabilité & Cartographie de NutriTrace assure le suivi bout-en-bout d'un produit alimentaire depuis sa production jusqu'à sa distribution, son stockage, son transport et sa mise à disposition du consommateur.

Le module ne se limite pas à enregistrer des statuts. Il doit permettre de reconstruire :

- ce qui est arrivé ;
- à quel lot ;
- quand ;
- où ;
- par quel acteur ;
- dans quelle quantité ;
- vers quelle destination ;
- par quel moyen de transport.

La traçabilité repose sur un journal chronologique d'événements, associé à une représentation géographique du parcours.

Le module doit ainsi fournir :

- traçabilité complète des lots ;
- historique chronologique ;
- historique géographique ;
- localisation des étapes ;
- cartographie interactive ;
- visualisation des acteurs ;
- visualisation des trajets ;
- calcul des distances ;
- estimation des émissions CO₂ ;
- traçabilité des transformations ;
- traçabilité des distributions ;
- intégration des chambres froides ;
- détection d'incohérences ;
- alertes ;
- blocage et rappel ;
- consultation publique contrôlée ;
- audit des opérations.

---

## 2. Objectifs fonctionnels

### O1 — Traçabilité complète
Permettre de reconstruire le parcours d'un lot depuis sa production jusqu'à son dernier événement connu.

### O2 — Traçabilité temporelle
Conserver une timeline ordonnée des événements :

Production → Stockage → Transformation → Chambre froide → Expédition → Transport → Réception → Distribution

Les étapes réellement applicables dépendent du cycle de vie du lot.

### O3 — Traçabilité géographique
Chaque événement localisable doit être associé à une localisation.

Le système doit permettre de répondre à la question :

- Où se trouvait le lot à un instant donné ?

### O4 — Cartographie
Afficher sur une carte :

- producteurs ;
- transformateurs ;
- distributeurs ;
- chambres froides ;
- points de départ ;
- points de réception ;
- étapes intermédiaires ;
- trajet de transport.

### O5 — Traçabilité des transformations
Lorsqu'un lot est transformé, le système doit conserver la relation entre :

- Lot parent ;
- Transformation ;
- Lot enfant.

La lignée doit rester consultable.

### O6 — Traçabilité des mouvements
Chaque mouvement inter-organisation doit être enregistré avec :

- origine ;
- destination ;
- quantité ;
- date ;
- acteur ;
- transport ;
- distance ;
- localisation.

### O7 — Empreinte environnementale
Calculer ou enregistrer les émissions associées au transport :

CO₂ = distance × facteur d'émission × quantité transportée

Le facteur d'émission doit être configurable.

### O8 — Détection d'anomalies
Identifier automatiquement les incohérences du parcours.

Exemples :

- événement sans localisation alors qu'une localisation est obligatoire ;
- quantité incohérente ;
- mouvement impossible ;
- réception avant expédition ;
- retour géographique incohérent ;
- lot bloqué faisant l'objet d'un transfert ;
- rupture anormale de timeline ;
- dépassement d'un délai de transport ;
- sortie de chambre froide sans entrée correspondante.

---

## 3. Périmètre fonctionnel

Le module couvre :

Produit → Lot → Événement → Transformation → Stockage / Chambre froide → Distribution → Shipment → Transport → Réception → Consommateur

Le module couvre également la dimension géographique :

Actor / Location → TraceabilityEvent → GeoPoint → Map → Route → Distance / CO₂

---

## 4. Acteurs

### 4.1 Producteur
Le producteur peut :

- créer un produit ;
- créer un lot ;
- déclarer la production ;
- localiser le lieu de production ;
- consulter la timeline ;
- consulter la carte ;
- consulter les alertes ;
- demander le blocage d'un lot ;
- consulter l'historique de ses lots.

### 4.2 Transformateur
Le transformateur peut :

- réceptionner un lot ;
- effectuer une transformation ;
- créer un lot enfant ;
- déclarer les quantités entrantes ;
- déclarer les quantités sortantes ;
- déclarer les pertes ;
- consulter la lignée ;
- consulter la cartographie.

### 4.3 Distributeur
Le distributeur peut :

- réceptionner des lots ;
- préparer une distribution ;
- créer une expédition ;
- déclarer un départ ;
- suivre un transport ;
- déclarer l'arrivée ;
- déclarer la réception ;
- consulter le parcours géographique.

### 4.4 Opérateur chambre froide
Peut :

- enregistrer une entrée ;
- enregistrer une sortie ;
- enregistrer une perte ;
- déclarer une température ;
- consulter les lots stockés ;
- déclencher une alerte.

### 4.5 Administrateur
Possède une visibilité globale sur :

- lots ;
- acteurs ;
- événements ;
- mouvements ;
- alertes ;
- cartes ;
- statistiques ;
- anomalies.

### 4.6 Consommateur
Sans authentification, lorsqu'il dispose d'un code public :

- /trace/{code}

Il peut consulter les informations autorisées du passeport du lot :

- produit ;
- origine ;
- étapes principales ;
- acteurs autorisés ;
- historique synthétique ;
- parcours géographique public ;
- certifications éventuelles.

Les données sensibles internes ne sont jamais exposées.

---

## 5. Entités métier V2

### 5.1 Product

Le produit représente le référentiel commercial ou agricole.

Product
- id
- organization_id
- name
- category
- description
- origin_country
- default_unit
- status
- created_at
- updated_at
- deleted_at

Relations :

- Organization 1 → * Product
- Product 1 → * Lot

### 5.2 Lot

Le lot est l'agrégat central du module.

Lot
- id
- product_id
- organization_id
- parent_batch_id
- lot_number
- quantity
- unit
- status
- production_location_id
- produced_at
- expires_at
- public_token
- metadata
- created_at
- updated_at
- deleted_at

Statuts :

- ACTIVE
- IN_TRANSIT
- STORED
- TRANSFORMED
- DISTRIBUTED
- SOLD
- BLOCKED
- RECALLED
- EXPIRED
- DESTROYED

Relation récursive :

Lot parent → Lot enfant

Cela permet de reconstruire la généalogie d'un produit.

---

## 6. TraceabilityEvent

Le TraceabilityEvent devient le journal de preuve du système.

TraceabilityEvent
- id
- lot_id
- organization_id
- actor_user_id
- location_id
- type
- occurred_at
- quantity
- unit
- title
- notes
- meta
- previous_event_id
- created_at

Types :

- PRODUCTION
- COLLECTED
- RECEIPT
- TRANSFORMATION
- PACKAGING
- STORED
- MOVED
- INSPECTED
- TEMPERATURE_CHECKED
- LOADED
- DISPATCHED
- IN_TRANSIT
- ARRIVED
- DELIVERED
- DISTRIBUTION
- REJECTED
- DAMAGED
- LOST
- EXPIRED
- REDISTRIBUTED
- SALE
- CONSUMPTION
- LOSS
- WASTE_DECLARED
- VALORIZED
- COLD_STORAGE_ENTRY
- COLD_STORAGE_EXIT
- BLOCKED
- RECALLED

Principe important :

Un événement métier significatif produit une trace.

L'événement n'est pas simplement un statut.

---

## 7. Transformation

La transformation est une entité métier indépendante.

Transformation
- id
- input_batch_id
- output_batch_id
- organization_id
- process_name
- input_quantity
- output_quantity
- loss_quantity
- location_id
- occurred_at
- notes
- created_at

Relation :

Input Lot → Transformation → Output Lot

Règle :

input_quantity = output_quantity + loss_quantity

avec tolérance configurable.

Une transformation produit :

- un événement de transformation sur le lot entrant ;
- un événement de production sur le lot sortant.

---

## 8. Distribution

Distribution
- id
- batch_id
- source_organization_id
- destination_organization_id
- quantity
- unit
- transport_mode
- departure_at
- expected_arrival_at
- arrived_at
- distance_km
- status
- created_at
- updated_at

Une distribution représente le transfert physique ou logistique d'une quantité de lot.

---

## 9. Shipment

La distribution peut être associée à une expédition.

Shipment
- id
- distribution_id
- reference
- departure_location_id
- destination_location_id
- transport_mode
- carrier
- vehicle_reference
- departed_at
- expected_arrival_at
- arrived_at
- distance_km
- status
- route_geometry
- created_at
- updated_at

ShipmentItem
- id
- shipment_id
- batch_id
- quantity
- unit

Cela permet d'avoir :

Shipment → ShipmentItem → Lot A

---

## 10. Localisation

La localisation devient une vraie composante du module.

Location
- id
- name
- type
- address
- city
- governorate
- latitude
- longitude
- metadata

Types :

- PRODUCTION_SITE
- TRANSFORMATION_SITE
- WAREHOUSE
- COLD_ROOM
- DISTRIBUTION_CENTER
- RETAIL_POINT
- OTHER

Cela évite d'utiliser simplement une chaîne texte pour représenter une donnée géographique.

---

## 11. Historique géographique

Chaque événement localisable doit pouvoir être représenté par :

TraceabilityEvent → Location → latitude / longitude

La carte peut ainsi reconstruire :

- producteur ;
- transformateur ;
- chambre froide ;
- distributeur ;
- autres points logistiques.

---

## 12. Cartographie interactive

La carte est une projection de la traçabilité, et non une donnée indépendante.

Elle doit permettre :

### 12.1 Marqueurs
Afficher :

- producteur ;
- transformateur ;
- distributeur ;
- chambre froide ;
- autre point logistique.

Chaque marqueur affiche :

- nom ;
- type d'acteur ;
- localisation ;
- événements associés ;
- lots concernés.

### 12.2 Trajet
Afficher les segments :

Location A → Route → Location B

avec :

- distance ;
- date ;
- moyen de transport ;
- lot ;
- quantité ;
- émissions CO₂.

### 12.3 Historique géographique
L'utilisateur peut sélectionner un lot et voir son parcours sur la carte :

Production → Transformation → Cold Storage → Dispatch → Transport → Distribution

---

## 13. Recherche géographique

Le système doit permettre :

- recherche par lot ;
- recherche par produit ;
- recherche par acteur ;
- recherche par zone ;
- recherche par ville / gouvernorat ;
- recherche sur carte.

---

## 14. Calcul de distance

Pour chaque transport :

- distance_km est calculée ou enregistrée.

Deux niveaux peuvent être supportés :

### Niveau 1 — Distance géographique
Calcul entre point A et point B.

### Niveau 2 — Distance routière
Utilisation d'un moteur de routage lorsque disponible.

Le système conserve la distance utilisée dans la traçabilité afin que le résultat historique ne dépende pas uniquement d'un recalcul futur.

---

## 15. Calcul CO₂

Le système doit permettre d'estimer les émissions liées au transport.

Formule configurable :

CO₂ = distance_km × quantity_tonnes × emission_factor

Exemple :

- Distance = 120 km
- Quantité = 2 tonnes
- Facteur = 0.09 kg CO₂ / t.km
- CO₂ = 120 × 2 × 0.09 = 21.6 kg CO₂

Le facteur doit être associé au moyen de transport :

- ROAD
- REFRIGERATED_ROAD
- RAIL
- SEA
- AIR
- OTHER

Le résultat est enregistré avec l'expédition.

---

## 16. Chambre froide / chaîne du froid

La chaîne du froid est intégrée à la timeline.

Événements associés :

- COLD_STORAGE_ENTRY
- COLD_STORAGE_EXIT
- TEMPERATURE_CHECKED
- LOSS

Exemple :

Production → ColdStorageEntry → TemperatureChecked → ColdStorageExit → Shipment

Une anomalie de température peut générer une TraceAlert.

---

## 17. TraceAlert

TraceAlert
- id
- lot_id
- traceability_event_id
- type
- severity
- message
- status
- detected_at
- resolved_at
- resolved_by
- metadata

Types :

- QUANTITY
- LOCATION
- TIMELINE
- STORAGE
- TEMPERATURE
- TRANSPORT
- STATUS
- TRACEABILITY

Gravité :

- INFO
- WARNING
- HIGH
- CRITICAL

Statut :

- OPEN
- ACKNOWLEDGED
- RESOLVED
- DISMISSED

---

## 18. Détection d'anomalies

Le système doit avoir un composant de détection :

TraceabilityAnomalyDetector

Il analyse les événements.

### Exemples d'anomalies

- arrivée avant expédition ;
- quantité incohérente ;
- lot bloqué transféré ;
- événement sans localisation ;
- rupture d'ordre chronologique ;
- sortie de chambre froide sans entrée correspondante ;
- expédition sans destination connue.

---

## 19. Blocage

Un lot bloqué :

- status = BLOCKED

ne peut plus effectuer certaines opérations critiques.

Le système crée un événement :

- TraceabilityEvent type = BLOCKED

avec :

- raison ;
- acteur ;
- localisation ;
- date de survenance.

L'action doit être auditée.

---

## 20. Rappel

Le rappel devient un workflow métier.

ACTIVE → RECALLED

Le système conserve :

- raison ;
- auteur ;
- date ;
- événements ;
- lots concernés ;
- descendants éventuels.

Un lot parent rappelé peut entraîner la recherche des lots descendants pour identifier les produits potentiellement impactés.

---

## 21. Lignée upstream / downstream

Le module doit supporter deux types de recherche.

### Upstream
Lot C → Lot B → Lot A

Question :

- D'où vient ce produit ?

### Downstream
Lot A → Lot B → Lot C

Question :

- Quels produits ou lots ont été générés à partir de ce lot ?

Cela est particulièrement important pour le rappel.

---

## 22. Timeline avancée

La timeline doit afficher des événements détaillés :

- date et heure ;
- type d'événement ;
- quantité ;
- acteur ;
- lieu ;
- notes ;
- statut ;
- métadonnées.

Chaque événement est cliquable pour afficher ses détails complets.

---

## 23. Passeport public

Chaque lot peut disposer d'un public_token.

Exemple :

- /trace/{token}

La page publique présente :

- produit ;
- origine ;
- date de production ;
- certifications publiques ;
- étapes principales ;
- carte simplifiée ;
- historique ;
- statut public.

Les informations internes comme :

- données personnelles ;
- métadonnées techniques ;
- informations sensibles ;
- règles de détection ;

ne doivent pas être exposées.

---

## 24. API V2

### Lots
- GET /api/v1/batches
- GET /api/v1/batches/{batch}

### Timeline
- GET /api/v1/batches/{batch}/timeline

### Lignée
- GET /api/v1/batches/{batch}/upstream
- GET /api/v1/batches/{batch}/downstream

### Carte
- GET /api/v1/batches/{batch}/map

### Événements
- GET /api/v1/batches/{batch}/events
- POST /api/v1/batches/{batch}/events

### Distribution
- POST /api/v1/batches/{batch}/distributions
- GET /api/v1/distributions/{distribution}

### Shipment
- POST /api/v1/shipments
- GET /api/v1/shipments/{shipment}

### Alertes
- GET /api/v1/batches/{batch}/alerts
- POST /api/v1/alerts/{alert}/resolve

### Passeport public
- GET /api/v1/public/traceability/{token}

---

## 25. Dashboard V2

Le dashboard doit fournir :

### KPIs
- Total lots
- Lots actifs
- Lots en transit
- Lots stockés
- Lots bloqués
- Lots rappelés
- Lots expirés

### Traçabilité
- événements / lot
- pourcentage de couverture
- lots sans événement
- lots avec anomalies

### Logistique
- distance totale
- nombre de transports
- temps moyen de transport

### Environnement
- CO₂ total
- CO₂ moyen / lot
- CO₂ par mode de transport

### Géographie
- nombre d'acteurs
- nombre de points logistiques
- nombre de trajets
- zones avec activité

---

## 26. Architecture Laravel 12

Le projet reste un monolithe MVC Laravel 12.

Laravel Application
- Controllers
- Form Requests
- Policies
- Services
- Actions
- Domain
  - Traceability
  - Cartography
  - Shipment
  - ColdChain
- Models
- Events
- Listeners
- Jobs
- Resources

Le module peut rester dans Laravel tout en ayant une séparation métier forte.

---

## 27. Services métier

Au lieu d'un énorme TraceabilityService, il est recommandé d'avoir des services spécialisés :

- TraceabilityService
- TraceabilityTimelineBuilder
- TraceabilityChainBuilder
- TraceabilityAnomalyDetector
- TraceabilityAnalyticsService
- GeolocationService
- RouteService
- CarbonEmissionService
- ColdChainManager
- ShipmentManager

---

## 28. Actions métier

Les opérations critiques peuvent être représentées par des Actions :

- CreateBatch
- RecordTraceabilityEvent
- RecordTransformation
- RecordDistribution
- CreateShipment
- DispatchShipment
- ReceiveShipment
- RecordColdRoomMovement
- BlockBatch
- RecallBatch
- ResolveTraceAlert

Cela permet d'éviter une logique métier complexe dans les controllers.

---

## 29. Events / Listeners

Exemple :

BatchCreated → UpdateTraceability → DetectAnomalies → GenerateAlerts → UpdateAnalytics

Pour un shipment :

ShipmentDispatched → CreateTraceabilityEvents → CalculateDistance → CalculateCO2 → DetectTransportAnomalies

---

## 30. Transactions et intégrité

Les opérations métier importantes doivent utiliser les transactions DB.

Exemple transformation :

BEGIN TRANSACTION

- Create Transformation
- Create child Lot
- Create transformation event
- Create production event
- Update parent status

COMMIT

Si une étape échoue :

ROLLBACK

Ainsi, on évite des états incohérents.

---

## 31. Append-only

Les événements de traçabilité doivent être considérés comme un journal append-only.

On évite :

- UPDATE traceability_events
- DELETE traceability_events

Pour une correction, on crée un nouvel événement correctif.

Exemple :

EVENT : quantity = 500

CORRECTION_EVENT : original_event_id = X, quantity = 480, reason = "Erreur de saisie"

Cela donne un historique auditable.

---

## 32. Chaînage des événements

La V2 peut exploiter previous_event_id pour obtenir une séquence :

Event A → Event B → Event C → Event D

Cela permet de détecter une rupture éventuelle de séquence.

---

## 33. Architecture de données

Le cœur du modèle devient :

Organization
- Product
- Lot
- Events
- Transformation
- Distribution
- Shipment

Et autour :

Lot
- Location
- ColdRoomMovement
- TraceAlert
- EnvironmentalImpact
- parent / children

---

## 34. Cartographie : architecture technique

La carte ne doit pas stocker une deuxième vérité métier.

La source de vérité reste :

- TraceabilityEvent
- Distribution
- Shipment
- Location

La carte est une vue / projection de ces données.

Database → Traceability Query → Map DTO → Frontend Map

Exemple de DTO :

{
  "batch": "NT-2026-000123",
  "points": [
    {
      "type": "production",
      "latitude": 36.8065,
      "longitude": 10.1815
    },
    {
      "type": "transformation",
      "latitude": 36.832,
      "longitude": 10.21
    }
  ],
  "routes": [
    {
      "distance_km": 22,
      "transport_mode": "refrigerated_road"
    }
  ]
}

---

## 35. Synthèse fonctionnelle

Le module V2 de NutriTrace vise à transformer la traçabilité d'un simple historique d'états en un système complet de suivi de valeur, de confiance, de conformité, de sécurité et de cartographie.

Le module doit permettre :

- de suivre chaque lot ;
- de rendre visible son réseau de transformation ;
- de mesurer son parcours géographique ;
- de calculer son impact environnemental ;
- de détecter les écarts ou anomalies ;
- d'alerter en cas de risque ;
- de rappeler les lots concernés ;
- de partager un passeport public contrôlé.

---

## 36. Conclusion

Le module Traçabilité & Cartographie V2 est une évolution structurée du module de base. Il ne se limite plus à un journal d'événements, mais devient un système complet d'observabilité produit, de conformité logistique et de gestion de risques.

Il repose sur une architecture cohérente :

- lot comme unité de suivi ;
- événement comme preuve ;
- localisation comme dimension géographique ;
- transformation comme lien de production ;
- shipment/distribution comme chaîne logistique ;
- alertes comme mécanisme de sécurité ;
- carte comme vue de synthèse ;
- public traceability comme interface de confiance.

Cette version V2 correspond à une cible de production robuste, extensible et alignée avec les exigences métier d'une filière alimentaire moderne.
