import 'package:flutter/material.dart';
import '../models/models.dart';
import '../services/api_service.dart';

class WalletProvider extends ChangeNotifier {
  final ApiService _api = ApiService();
  double _balance = 0;
  List<Transaction> _transactions = [];
  bool _isLoading = false;
  String? _error;

  double get balance => _balance;
  List<Transaction> get transactions => _transactions;
  bool get isLoading => _isLoading;
  String? get error => _error;

  Future<void> fetchBalance() async {
    try {
      final data = await _api.getWalletBalance();
      _balance = double.parse(data['balance'].toString());
      notifyListeners();
    } catch (_) {}
  }

  Future<bool> addMoney(double amount) async {
    _isLoading = true;
    _error = null;
    notifyListeners();

    try {
      final data = await _api.addMoney(amount);
      _balance = double.parse(data['balance'].toString());
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

  Future<void> fetchTransactions() async {
    _isLoading = true;
    notifyListeners();

    try {
      final data = await _api.getWalletTransactions();
      final list = data['data'] as List;
      _transactions = list.map((e) => Transaction.fromJson(e)).toList();
    } catch (_) {}

    _isLoading = false;
    notifyListeners();
  }
}
