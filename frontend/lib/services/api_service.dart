import 'dart:convert';
import 'dart:io';
import 'package:flutter/foundation.dart';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';

class ApiService {
  // Set this to your computer's local IP for physical device testing
  static const String _localIp = '192.168.0.190';

  static final String baseUrl = _buildBaseUrl();

  static const Duration _timeout = Duration(seconds: 15);

  static String _buildBaseUrl() {
    if (kIsWeb) {
      return 'http://localhost:8000/api';
    }
    // Physical devices and emulators both use the local IP
    // 10.0.2.2 only works on Android emulator, so using actual IP works everywhere
    return 'http://$_localIp:8000/api';
  }

  Future<String?> _getToken() async {
    final prefs = await SharedPreferences.getInstance();
    return prefs.getString('auth_token');
  }

  Future<Map<String, String>> _headers({bool auth = true}) async {
    final headers = <String, String>{
      'Content-Type': 'application/json',
      'Accept': 'application/json',
    };
    if (auth) {
      final token = await _getToken();
      if (token != null) {
        headers['Authorization'] = 'Bearer $token';
      }
    }
    return headers;
  }

  Future<Map<String, dynamic>> _handleResponse(http.Response response) async {
    try {
      final body = jsonDecode(response.body) as Map<String, dynamic>;
      if (response.statusCode >= 200 && response.statusCode < 300) {
        return body;
      }
      throw ApiException(
        body['message'] ?? 'Something went wrong',
        response.statusCode,
      );
    } on FormatException {
      throw ApiException(
        'Server returned an invalid response',
        response.statusCode,
      );
    }
  }

  // ── Auth ──

  Future<Map<String, dynamic>> register({
    required String name,
    required String email,
    required String password,
    required String passwordConfirmation,
    String? phone,
  }) async {
    final response = await http.post(
      Uri.parse('$baseUrl/register'),
      headers: await _headers(auth: false),
      body: jsonEncode({
        'name': name,
        'email': email,
        'password': password,
        'password_confirmation': passwordConfirmation,
        'phone': phone,
      }),
    ).timeout(_timeout);
    return _handleResponse(response);
  }

  Future<Map<String, dynamic>> login({
    required String email,
    required String password,
  }) async {
    final response = await http.post(
      Uri.parse('$baseUrl/login'),
      headers: await _headers(auth: false),
      body: jsonEncode({'email': email, 'password': password}),
    ).timeout(_timeout);
    return _handleResponse(response);
  }

  Future<void> logout() async {
    await http.post(Uri.parse('$baseUrl/logout'), headers: await _headers())
        .timeout(_timeout);
    final prefs = await SharedPreferences.getInstance();
    await prefs.remove('auth_token');
  }

  // ── Profile ──

  Future<Map<String, dynamic>> getProfile() async {
    final response = await http.get(
      Uri.parse('$baseUrl/profile'),
      headers: await _headers(),
    ).timeout(_timeout);
    return _handleResponse(response);
  }

  Future<Map<String, dynamic>> updateProfile({
    String? name,
    String? phone,
  }) async {
    final body = <String, dynamic>{};
    if (name != null) body['name'] = name;
    if (phone != null) body['phone'] = phone;

    final response = await http.put(
      Uri.parse('$baseUrl/profile'),
      headers: await _headers(),
      body: jsonEncode(body),
    );
    return _handleResponse(response);
  }

  // ── Wallet ──

  Future<Map<String, dynamic>> getWalletBalance() async {
    final response = await http.get(
      Uri.parse('$baseUrl/wallet/balance'),
      headers: await _headers(),
    );
    return _handleResponse(response);
  }

  Future<Map<String, dynamic>> addMoney(double amount) async {
    final response = await http.post(
      Uri.parse('$baseUrl/wallet/add-money'),
      headers: await _headers(),
      body: jsonEncode({'amount': amount}),
    );
    return _handleResponse(response);
  }

  Future<Map<String, dynamic>> getWalletTransactions() async {
    final response = await http.get(
      Uri.parse('$baseUrl/wallet/transactions'),
      headers: await _headers(),
    );
    return _handleResponse(response);
  }

  // ── Charging ──

  Future<Map<String, dynamic>> startCharging(
    double startPercentage, {
    int? stationId,
  }) async {
    final body = <String, dynamic>{'start_percentage': startPercentage};
    if (stationId != null) body['station_id'] = stationId;

    final response = await http.post(
      Uri.parse('$baseUrl/charging/start'),
      headers: await _headers(),
      body: jsonEncode(body),
    );
    return _handleResponse(response);
  }

  Future<Map<String, dynamic>> stopCharging(
    int sessionId,
    double endPercentage,
  ) async {
    final response = await http.post(
      Uri.parse('$baseUrl/charging/$sessionId/stop'),
      headers: await _headers(),
      body: jsonEncode({'end_percentage': endPercentage}),
    );
    return _handleResponse(response);
  }

  Future<Map<String, dynamic>> getActiveSession() async {
    final response = await http.get(
      Uri.parse('$baseUrl/charging/active'),
      headers: await _headers(),
    );
    return _handleResponse(response);
  }

  Future<Map<String, dynamic>> getChargingHistory() async {
    final response = await http.get(
      Uri.parse('$baseUrl/charging/history'),
      headers: await _headers(),
    );
    return _handleResponse(response);
  }

  // ── Transaction History ──

  Future<Map<String, dynamic>> getTransactionHistory() async {
    final response = await http.get(
      Uri.parse('$baseUrl/profile/transactions'),
      headers: await _headers(),
    );
    return _handleResponse(response);
  }

  // ── Stations ──

  Future<Map<String, dynamic>> getStations() async {
    final response = await http.get(
      Uri.parse('$baseUrl/stations'),
      headers: await _headers(),
    );
    return _handleResponse(response);
  }

  Future<List<dynamic>> getNearbyStations(
    double latitude,
    double longitude, {
    double radius = 10,
  }) async {
    final response = await http.get(
      Uri.parse(
        '$baseUrl/stations/nearby?latitude=$latitude&longitude=$longitude&radius=$radius',
      ),
      headers: await _headers(),
    );
    final body = jsonDecode(response.body);
    if (response.statusCode >= 200 && response.statusCode < 300) {
      return body as List<dynamic>;
    }
    throw ApiException(
      body['message'] ?? 'Something went wrong',
      response.statusCode,
    );
  }

  Future<Map<String, dynamic>> getStation(int id) async {
    final response = await http.get(
      Uri.parse('$baseUrl/stations/$id'),
      headers: await _headers(),
    );
    return _handleResponse(response);
  }

  // ── Payments ──

  Future<Map<String, dynamic>> initiatePayment(
    String gateway,
    double amount,
  ) async {
    final response = await http.post(
      Uri.parse('$baseUrl/payments/initiate'),
      headers: await _headers(),
      body: jsonEncode({'gateway': gateway, 'amount': amount}),
    );
    return _handleResponse(response);
  }

  Future<Map<String, dynamic>> verifyEsewaPayment(String transactionId) async {
    final response = await http.post(
      Uri.parse('$baseUrl/payments/verify/esewa'),
      headers: await _headers(),
      body: jsonEncode({'transaction_id': transactionId}),
    );
    return _handleResponse(response);
  }

  Future<Map<String, dynamic>> verifyKhaltiPayment(String pidx) async {
    final response = await http.post(
      Uri.parse('$baseUrl/payments/verify/khalti'),
      headers: await _headers(),
      body: jsonEncode({'pidx': pidx}),
    );
    return _handleResponse(response);
  }

  // ── Subscriptions ──

  Future<List<dynamic>> getSubscriptionPlans() async {
    final response = await http.get(
      Uri.parse('$baseUrl/subscriptions/plans'),
      headers: await _headers(),
    );
    final body = jsonDecode(response.body);
    if (response.statusCode >= 200 && response.statusCode < 300) {
      return body as List<dynamic>;
    }
    throw ApiException(
      body['message'] ?? 'Something went wrong',
      response.statusCode,
    );
  }

  Future<Map<String, dynamic>> subscribe(int planId) async {
    final response = await http.post(
      Uri.parse('$baseUrl/subscriptions/subscribe'),
      headers: await _headers(),
      body: jsonEncode({'plan_id': planId}),
    );
    return _handleResponse(response);
  }

  Future<Map<String, dynamic>> getCurrentSubscription() async {
    final response = await http.get(
      Uri.parse('$baseUrl/subscriptions/current'),
      headers: await _headers(),
    );
    return _handleResponse(response);
  }

  Future<Map<String, dynamic>> cancelSubscription() async {
    final response = await http.post(
      Uri.parse('$baseUrl/subscriptions/cancel'),
      headers: await _headers(),
    );
    return _handleResponse(response);
  }

  // ── Notifications ──

  Future<Map<String, dynamic>> getNotifications() async {
    final response = await http.get(
      Uri.parse('$baseUrl/notifications'),
      headers: await _headers(),
    );
    return _handleResponse(response);
  }

  Future<void> markNotificationAsRead(int id) async {
    await http.post(
      Uri.parse('$baseUrl/notifications/$id/read'),
      headers: await _headers(),
    );
  }

  Future<void> markAllNotificationsAsRead() async {
    await http.post(
      Uri.parse('$baseUrl/notifications/read-all'),
      headers: await _headers(),
    );
  }

  Future<Map<String, dynamic>> getUnreadNotificationCount() async {
    final response = await http.get(
      Uri.parse('$baseUrl/notifications/unread-count'),
      headers: await _headers(),
    );
    return _handleResponse(response);
  }

  Future<void> registerDeviceToken(String token, String platform) async {
    await http.post(
      Uri.parse('$baseUrl/notifications/token'),
      headers: await _headers(),
      body: jsonEncode({'token': token, 'platform': platform}),
    );
  }
}

class ApiException implements Exception {
  final String message;
  final int statusCode;

  ApiException(this.message, this.statusCode);

  @override
  String toString() => message;
}
