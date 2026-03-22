import 'package:flutter/material.dart';
import '../models/models.dart';
import '../services/api_service.dart';

class StationProvider extends ChangeNotifier {
  final ApiService _api = ApiService();
  List<ChargingStation> _stations = [];
  List<ChargingStation> _nearbyStations = [];
  bool _isLoading = false;
  String? _error;

  List<ChargingStation> get stations => _stations;
  List<ChargingStation> get nearbyStations => _nearbyStations;
  bool get isLoading => _isLoading;
  String? get error => _error;

  Future<void> fetchStations() async {
    _isLoading = true;
    _error = null;
    notifyListeners();

    try {
      final data = await _api.getStations();
      final list = data['data'] as List;
      _stations = list.map((e) => ChargingStation.fromJson(e)).toList();
    } on ApiException catch (e) {
      _error = e.message;
    } catch (_) {
      _error = 'Failed to load stations';
    }

    _isLoading = false;
    notifyListeners();
  }

  Future<void> fetchNearbyStations(
    double latitude,
    double longitude, {
    double radius = 10,
  }) async {
    _isLoading = true;
    _error = null;
    notifyListeners();

    try {
      final list = await _api.getNearbyStations(
        latitude,
        longitude,
        radius: radius,
      );
      _nearbyStations = list.map((e) => ChargingStation.fromJson(e)).toList();
    } on ApiException catch (e) {
      _error = e.message;
    } catch (_) {
      _error = 'Failed to load nearby stations';
    }

    _isLoading = false;
    notifyListeners();
  }
}
