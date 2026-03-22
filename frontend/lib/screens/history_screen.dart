import 'package:flutter/material.dart';
import 'package:intl/intl.dart';
import '../models/models.dart';
import '../services/api_service.dart';

class HistoryScreen extends StatefulWidget {
  final bool showTransactions;

  const HistoryScreen({super.key, this.showTransactions = false});

  @override
  State<HistoryScreen> createState() => _HistoryScreenState();
}

class _HistoryScreenState extends State<HistoryScreen>
    with SingleTickerProviderStateMixin {
  late TabController _tabController;
  final ApiService _api = ApiService();

  List<ChargingSession> _sessions = [];
  List<Transaction> _transactions = [];
  bool _loadingSessions = true;
  bool _loadingTransactions = true;

  @override
  void initState() {
    super.initState();
    _tabController = TabController(
      length: 2,
      vsync: this,
      initialIndex: widget.showTransactions ? 1 : 0,
    );
    _loadSessions();
    _loadTransactions();
  }

  @override
  void dispose() {
    _tabController.dispose();
    super.dispose();
  }

  Future<void> _loadSessions() async {
    try {
      final data = await _api.getChargingHistory();
      final list = data['data'] as List;
      setState(() {
        _sessions = list.map((e) => ChargingSession.fromJson(e)).toList();
        _loadingSessions = false;
      });
    } catch (_) {
      setState(() => _loadingSessions = false);
    }
  }

  Future<void> _loadTransactions() async {
    try {
      final data = await _api.getTransactionHistory();
      final list = data['data'] as List;
      setState(() {
        _transactions = list.map((e) => Transaction.fromJson(e)).toList();
        _loadingTransactions = false;
      });
    } catch (_) {
      setState(() => _loadingTransactions = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('History'),
        bottom: TabBar(
          controller: _tabController,
          tabs: const [
            Tab(text: 'Charging Sessions'),
            Tab(text: 'Transactions'),
          ],
        ),
      ),
      body: TabBarView(
        controller: _tabController,
        children: [
          // Charging Sessions Tab
          _loadingSessions
              ? const Center(child: CircularProgressIndicator())
              : _sessions.isEmpty
              ? const Center(child: Text('No charging sessions'))
              : RefreshIndicator(
                  onRefresh: _loadSessions,
                  child: ListView.builder(
                    padding: const EdgeInsets.all(16),
                    itemCount: _sessions.length,
                    itemBuilder: (_, i) => _SessionTile(session: _sessions[i]),
                  ),
                ),

          // Transactions Tab
          _loadingTransactions
              ? const Center(child: CircularProgressIndicator())
              : _transactions.isEmpty
              ? const Center(child: Text('No transactions'))
              : RefreshIndicator(
                  onRefresh: _loadTransactions,
                  child: ListView.builder(
                    padding: const EdgeInsets.all(16),
                    itemCount: _transactions.length,
                    itemBuilder: (_, i) =>
                        _TransactionTile(transaction: _transactions[i]),
                  ),
                ),
        ],
      ),
    );
  }
}

class _SessionTile extends StatelessWidget {
  final ChargingSession session;

  const _SessionTile({required this.session});

  @override
  Widget build(BuildContext context) {
    final dateStr = DateFormat(
      'dd MMM yyyy, hh:mm a',
    ).format(session.startedAt);
    final isCompleted = session.status == 'completed';

    return Card(
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                Text(dateStr, style: Theme.of(context).textTheme.bodySmall),
                Chip(
                  label: Text(
                    session.status.toUpperCase(),
                    style: const TextStyle(fontSize: 10),
                  ),
                  backgroundColor: isCompleted
                      ? Colors.green.shade50
                      : Colors.orange.shade50,
                  padding: EdgeInsets.zero,
                  materialTapTargetSize: MaterialTapTargetSize.shrinkWrap,
                ),
              ],
            ),
            const SizedBox(height: 8),
            Row(
              children: [
                Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      const Text(
                        'Start',
                        style: TextStyle(color: Colors.grey, fontSize: 12),
                      ),
                      Text(
                        '${session.startPercentage.toStringAsFixed(0)}%',
                        style: const TextStyle(fontWeight: FontWeight.bold),
                      ),
                    ],
                  ),
                ),
                if (isCompleted) ...[
                  const Icon(Icons.arrow_forward, size: 16, color: Colors.grey),
                  Expanded(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.center,
                      children: [
                        const Text(
                          'End',
                          style: TextStyle(color: Colors.grey, fontSize: 12),
                        ),
                        Text(
                          '${session.endPercentage?.toStringAsFixed(0) ?? '-'}%',
                          style: const TextStyle(fontWeight: FontWeight.bold),
                        ),
                      ],
                    ),
                  ),
                  Expanded(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.end,
                      children: [
                        const Text(
                          'Cost',
                          style: TextStyle(color: Colors.grey, fontSize: 12),
                        ),
                        Text(
                          '₹ ${session.cost?.toStringAsFixed(2) ?? '-'}',
                          style: const TextStyle(
                            fontWeight: FontWeight.bold,
                            color: Colors.red,
                          ),
                        ),
                      ],
                    ),
                  ),
                ],
              ],
            ),
          ],
        ),
      ),
    );
  }
}

class _TransactionTile extends StatelessWidget {
  final Transaction transaction;

  const _TransactionTile({required this.transaction});

  @override
  Widget build(BuildContext context) {
    final isCredit = transaction.type == 'credit';
    final dateStr = DateFormat(
      'dd MMM yyyy, hh:mm a',
    ).format(transaction.createdAt);

    return Card(
      child: ListTile(
        leading: CircleAvatar(
          backgroundColor: isCredit ? Colors.green.shade50 : Colors.red.shade50,
          child: Icon(
            isCredit ? Icons.arrow_downward : Icons.arrow_upward,
            color: isCredit ? Colors.green : Colors.red,
          ),
        ),
        title: Text(transaction.description),
        subtitle: Text(dateStr),
        trailing: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          crossAxisAlignment: CrossAxisAlignment.end,
          children: [
            Text(
              '${isCredit ? '+' : '-'} ₹${transaction.amount.toStringAsFixed(2)}',
              style: TextStyle(
                fontWeight: FontWeight.bold,
                color: isCredit ? Colors.green : Colors.red,
              ),
            ),
            Text(
              'Bal: ₹${transaction.balanceAfter.toStringAsFixed(2)}',
              style: const TextStyle(fontSize: 11, color: Colors.grey),
            ),
          ],
        ),
      ),
    );
  }
}
