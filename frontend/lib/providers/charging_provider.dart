import 'package:flutter/material.dart';
import '../models/models.dart';
import '../services/api_service.dart';

class ChargingProvider extends ChangeNotifier {
  final ApiService _api = ApiService();
  ChargingSession? _activeSession;
  List<ChargingSession> _history = [];
  bool _isLoading = false;
  String? _error;

  ChargingSession? get activeSession => _activeSession;
  List<ChargingSession> get history => _history;
  bool get isLoading => _isLoading;
  bool get isCharging => _activeSession != null;
  String? get error => _error;

  Future<void> fetchActiveSession() async {
    try {
      final data = await _api.getActiveSession();
      if (data['session'] != null) {
        _activeSession = ChargingSession.fromJson(data['session']);
      } else {
        _activeSession = null;
      }
      notifyListeners();
    } catch (_) {}
  }

  Future<bool> startCharging(double startPercentage) async {
    _isLoading = true;
    _error = null;
    notifyListeners();

    try {
      final data = await _api.startCharging(startPercentage);
      _activeSession = ChargingSession.fromJson(data['session']);
      _isLoading = false;
      notifyListeners();
      return true;
    } on ApiException catch (e) {
      _error = e.message;
      _isLoading = false;
      notifyListeners();
      return false;
    }
  }

  Future<bool> stopCharging(double endPercentage) async {
    if (_activeSession == null) return false;

    _isLoading = true;
    _error = null;
    notifyListeners();

    try {
      await _api.stopCharging(_activeSession!.id, endPercentage);
      _activeSession = null;
      _isLoading = false;
      notifyListeners();
      return true;
    } on ApiException catch (e) {
      _error = e.message;
      _isLoading = false;
      notifyListeners();
      return false;
    }
  }

  Future<void> fetchHistory() async {
    _isLoading = true;
    notifyListeners();

    try {
      final data = await _api.getChargingHistory();
      final list = data['data'] as List;
      _history = list.map((e) => ChargingSession.fromJson(e)).toList();
    } catch (_) {}

    _isLoading = false;
    notifyListeners();
  }
}
