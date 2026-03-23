import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:geolocator/geolocator.dart';
import 'package:url_launcher/url_launcher.dart';
import '../providers/station_provider.dart';
import '../models/models.dart';

class StationLocatorScreen extends StatefulWidget {
  const StationLocatorScreen({super.key});

  @override
  State<StationLocatorScreen> createState() => _StationLocatorScreenState();
}

class _StationLocatorScreenState extends State<StationLocatorScreen> {
  bool _useNearby = false;
  bool _locationLoading = false;
  String? _locationError;

  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addPostFrameCallback((_) {
      context.read<StationProvider>().fetchStations();
    });
  }

  Future<void> _findNearby() async {
    setState(() {
      _locationLoading = true;
      _locationError = null;
    });

    try {
      bool serviceEnabled = await Geolocator.isLocationServiceEnabled();
      if (!serviceEnabled) {
        setState(() {
          _locationError = 'Location services are disabled.';
          _locationLoading = false;
        });
        return;
      }

      LocationPermission permission = await Geolocator.checkPermission();
      if (permission == LocationPermission.denied) {
        permission = await Geolocator.requestPermission();
        if (permission == LocationPermission.denied) {
          setState(() {
            _locationError = 'Location permission denied.';
            _locationLoading = false;
          });
          return;
        }
      }

      if (permission == LocationPermission.deniedForever) {
        setState(() {
          _locationError = 'Location permission permanently denied.';
          _locationLoading = false;
        });
        return;
      }

      final position = await Geolocator.getCurrentPosition();
      if (!mounted) return;

      await context.read<StationProvider>().fetchNearbyStations(
        position.latitude,
        position.longitude,
      );

      setState(() {
        _useNearby = true;
        _locationLoading = false;
      });
    } catch (e) {
      setState(() {
        _locationError = 'Failed to get location.';
        _locationLoading = false;
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    final provider = context.watch<StationProvider>();
    final stations = _useNearby ? provider.nearbyStations : provider.stations;

    return Scaffold(
      appBar: AppBar(
        title: const Text('Charging Stations'),
        actions: [
          IconButton(
            icon: Icon(
              _useNearby ? Icons.list : Icons.near_me,
              color: _useNearby ? Colors.green : null,
            ),
            onPressed: _locationLoading ? null : _findNearby,
            tooltip: 'Find nearby stations',
          ),
        ],
      ),
      body: Column(
        children: [
          if (_locationError != null)
            Container(
              width: double.infinity,
              padding: const EdgeInsets.all(12),
              color: Colors.red.shade50,
              child: Text(
                _locationError!,
                style: TextStyle(color: Colors.red.shade700),
              ),
            ),
          if (_locationLoading) const LinearProgressIndicator(),
          Expanded(
            child: provider.isLoading
                ? const Center(child: CircularProgressIndicator())
                : stations.isEmpty
                ? const Center(child: Text('No stations found'))
                : RefreshIndicator(
                    onRefresh: () => provider.fetchStations(),
                    child: ListView.builder(
                      padding: const EdgeInsets.all(16),
                      itemCount: stations.length,
                      itemBuilder: (context, index) {
                        return _StationCard(station: stations[index]);
                      },
                    ),
                  ),
          ),
        ],
      ),
    );
  }
}

class _StationCard extends StatelessWidget {
  final ChargingStation station;

  const _StationCard({required this.station});

  @override
  Widget build(BuildContext context) {
    final isOnline = station.status == 'active';

    return Card(
      margin: const EdgeInsets.only(bottom: 12),
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Row(
              children: [
                Icon(
                  Icons.ev_station,
                  color: isOnline ? Colors.green : Colors.grey,
                  size: 28,
                ),
                const SizedBox(width: 12),
                Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(
                        station.name,
                        style: Theme.of(context).textTheme.titleMedium
                            ?.copyWith(fontWeight: FontWeight.bold),
                      ),
                      Text(
                        station.address,
                        style: Theme.of(context).textTheme.bodySmall,
                      ),
                    ],
                  ),
                ),
                Container(
                  padding: const EdgeInsets.symmetric(
                    horizontal: 8,
                    vertical: 4,
                  ),
                  decoration: BoxDecoration(
                    color: isOnline
                        ? Colors.green.shade50
                        : Colors.grey.shade200,
                    borderRadius: BorderRadius.circular(8),
                  ),
                  child: Text(
                    station.status.toUpperCase(),
                    style: TextStyle(
                      fontSize: 11,
                      fontWeight: FontWeight.bold,
                      color: isOnline
                          ? Colors.green.shade700
                          : Colors.grey.shade700,
                    ),
                  ),
                ),
                const SizedBox(width: 4),
                IconButton(
                  icon: const Icon(Icons.directions, color: Colors.blue),
                  tooltip: 'Navigate',
                  onPressed: () async {
                    final url = Uri.parse(
                      'https://www.google.com/maps/dir/?api=1&destination=${station.latitude},${station.longitude}',
                    );
                    if (await canLaunchUrl(url)) {
                      await launchUrl(
                        url,
                        mode: LaunchMode.externalApplication,
                      );
                    }
                  },
                ),
              ],
            ),
            const Divider(height: 20),
            Row(
              children: [
                _InfoChip(
                  icon: Icons.electrical_services,
                  label: station.chargerType ?? 'N/A',
                ),
                const SizedBox(width: 12),
                _InfoChip(
                  icon: Icons.bolt,
                  label: '${station.powerKw?.toStringAsFixed(0) ?? "?"} kW',
                ),
                const SizedBox(width: 12),
                _InfoChip(
                  icon: Icons.power,
                  label:
                      '${station.availablePorts}/${station.totalPorts} ports',
                ),
              ],
            ),
            if (station.distance != null) ...[
              const SizedBox(height: 8),
              Text(
                '${station.distance!.toStringAsFixed(1)} km away',
                style: TextStyle(
                  color: Colors.blue.shade700,
                  fontWeight: FontWeight.w500,
                ),
              ),
            ],
          ],
        ),
      ),
    );
  }
}

class _InfoChip extends StatelessWidget {
  final IconData icon;
  final String label;

  const _InfoChip({required this.icon, required this.label});

  @override
  Widget build(BuildContext context) {
    return Row(
      mainAxisSize: MainAxisSize.min,
      children: [
        Icon(icon, size: 14, color: Colors.grey.shade600),
        const SizedBox(width: 4),
        Text(label, style: Theme.of(context).textTheme.bodySmall),
      ],
    );
  }
}
