import 'package:flutter/material.dart';
import '../models/models.dart';
import '../services/api_service.dart';

class SubscriptionProvider extends ChangeNotifier {
  final ApiService _api = ApiService();
  List<SubscriptionPlan> _plans = [];
  UserSubscription? _currentSubscription;
  bool _isLoading = false;
  String? _error;

  List<SubscriptionPlan> get plans => _plans;
  UserSubscription? get currentSubscription => _currentSubscription;
  bool get isLoading => _isLoading;
  bool get hasActiveSubscription =>
      _currentSubscription != null && _currentSubscription!.isActive;
  String? get error => _error;

  Future<void> fetchPlans() async {
    _isLoading = true;
    notifyListeners();

    try {
      final list = await _api.getSubscriptionPlans();
      _plans = list.map((e) => SubscriptionPlan.fromJson(e)).toList();
    } catch (_) {}

    _isLoading = false;
    notifyListeners();
  }

  Future<void> fetchCurrentSubscription() async {
    try {
      final data = await _api.getCurrentSubscription();
      if (data.isNotEmpty) {
        _currentSubscription = UserSubscription.fromJson(data);
      } else {
        _currentSubscription = null;
      }
      notifyListeners();
    } catch (_) {
      _currentSubscription = null;
      notifyListeners();
    }
  }

  Future<bool> subscribe(int planId) async {
    _isLoading = true;
    _error = null;
    notifyListeners();

    try {
      final data = await _api.subscribe(planId);
      _currentSubscription = UserSubscription.fromJson(data['data']);
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

  Future<bool> cancelSubscription() async {
    _isLoading = true;
    _error = null;
    notifyListeners();

    try {
      await _api.cancelSubscription();
      _currentSubscription = null;
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
}
